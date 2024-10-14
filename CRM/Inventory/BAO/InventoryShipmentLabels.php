<?php

/**
 *
 */

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventoryShipmentLabels;

/**
 *
 */
class CRM_Inventory_BAO_InventoryShipmentLabels extends CRM_Inventory_DAO_InventoryShipmentLabels {
  use CRM_Inventory;

  const  SHIPPO_CARRIERS = ["UPS", "USPS"];
  const SHIPPO_PREFERRED_DOMESTIC_CARRIERS = ["UPS"];
  const SHIPPO_PREFERRED_INTERNATIONAL_CARRIERS = ["USPS"];

  /**
   * Function to preload the object.
   *
   * @param string $columName
   *   Field column.
   * @param mixed $value
   *   Field Value.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function load(string $columName = 'id', mixed $value = ''): void {
    $this->shipmentLabel = $this->findEntityById($columName, $value, 'InventoryShipmentLabels', TRUE);
    $this->sales = $this->getSales($this->shipmentLabel);
    $this->address = $this->getShipmentAddress($this->sales);
    $this->lineItem = $this->getSalesLineItems($this->sales);
    $this->productVariant = $this->getProductVariant($this->sales);
    $this->product = $this->getProduct($this->productVariant);
  }

  /**
   * Create a new InventoryShipmentLabels based on array-data.
   *
   * @param array $params
   *   Key-value pairs.
   *
   * @return CRM_Inventory_DAO_InventoryShipmentLabels|null
   *   Object of sales.
   */
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventoryShipmentLabels';
    $entityName = 'InventoryShipmentLabels';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    // Array data on shipment and purchase column converted in to json using
    // addListener.
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Function to get Shipment Label using id.
   *
   * @param string $searchColumn
   *   Search column.
   * @param int|string $searchValue
   *   Search value.
   * @param bool $returnObject
   *   Return object or array.
   *
   * @return array|object|null
   *   Shipment details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function findById2($searchColumn = 'id', int|string $searchValue = '', bool $returnObject = FALSE) {
    if ($returnObject) {
      $shipmentObj = new CRM_Inventory_BAO_InventoryShipmentLabels();
      $shipmentObj->$searchColumn = $searchValue;
      $shipmentObj->find(TRUE);
      return $shipmentObj;
    }
    else {
      $shipment = InventoryShipmentLabels::get(TRUE)
        ->addSelect('*')
        ->addWhere($searchColumn, '=', $searchValue)
        ->setLimit(1)
        ->execute();
      return $shipment->first();
    }
  }

  /**
   * Fetch the object and store the values in the values array.
   *
   * @param array $params
   *   Input parameters to find object.
   * @param array $values
   *   Output values of the object.
   *
   * @return array|null
   *   The found object or null
   */
  public static function getValues(array $params, array &$values): ?array {
    if (empty($params)) {
      return NULL;
    }
    $inventoryShipmentLabels = new CRM_Inventory_BAO_InventoryShipmentLabels();
    $inventoryShipmentLabels->copyValues($params);
    $inventoryShipmentLabels->find();
    $inventoryShipmentLabelsArray = [];
    while ($inventoryShipmentLabels->fetch()) {
      CRM_Core_DAO::storeValues($inventoryShipmentLabels, $values[$inventoryShipmentLabels->id]);
      $inventoryShipmentLabelsArray[$inventoryShipmentLabels->id] = $inventoryShipmentLabels;
    }
    return $inventoryShipmentLabelsArray;
  }

  /**
   * Get Rate and pay.
   *
   * Runs a delayed job in the background to get the rates and purchase label.
   *
   * If there is an error, we raise an exception so that the job will be
   * tried again.
   *
   * Signer is the name we send to shippo for who validated the customs
   * information.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function asyncGetRatesAndPay(): void {
    if (!$this->shipmentLabel->is_valid || $this->shipmentExpired()) {
      $this->getRates();
    }
    if (!$this->shipmentLabel->is_paid && $this->shipmentLabel->is_valid && $this->shipmentLabel->rate_id) {
      $this->pay();
    }
    if ($this->shipmentLabel->has_error) {
      throw new Exception($this->shipmentLabel->errorMessages());
    }
  }

  /**
   * Refund.
   *
   * @return void
   *   Nothing.
   *
   * @throws Exception
   */
  public function asyncRefund(): void {
    if ($this->shipmentLabel->is_paid) {
      $this->refund();
    }
    if ($this->shipmentLabel->has_error) {
      throw new Exception($this->errorMessages());
    }
  }

  /**
   * IS ready to pay for label.
   *
   * @return bool
   *   Is ready.
   */
  public function readyToPay(): bool {
    return $this->hasRates() && !$this->shipmentLabel->is_paid && !is_null($this->shipmentLabel->rate_id);
  }

  /**
   * Does label have rate?
   *
   * @return bool
   *   Has rates.
   */
  public function hasRates(): bool {
    return !empty($this->shipmentLabel->shipment['rates']);
  }

  /**
   *
   */
  public function errorMessages() {
    return implode('. ', array_filter(array_map(function ($m) {
      return $this->isErrorMessage($m) ? $m["text"] : NULL;
    }, $this->shipmentLabel->purchase['messages'])));
  }

  /**
   * Message during label generation.
   *
   * @return array
   *   Message.
   */
  public function messages(): array {
    $msg = [];
    if (!empty($this->shipmentLabel->shipment['messages'])) {
      $msg = array_merge($msg, $this->shipmentLabel->shipment['messages']);
    }
    if (!empty($this->shipmentLabel->purchase['messages'])) {
      $msg = array_merge($msg, $this->shipmentLabel->purchase['messages']);
    }
    return $msg;
  }

  /**
   * Check Shipment is expired.
   *
   * The shipment data is only valid for 24 hours.
   *
   * @return bool
   *   Is Expired.
   */
  public function shipmentExpired(): bool {
    if (!empty($this->shipmentLabel->shipment['shipment_date'])) {
      return strtotime($this->shipmentLabel->shipment['shipment_date']) < strtotime('-24 hours');
    }
    else {
      return TRUE;
    }
  }

  /**
   * Tracking Url.
   *
   * @return array|string|string[]
   *   Mixed info.
   */
  public function trackingUrl(): array|string {
    $environment = CRM_Core_Config::environment();
    if (!empty($this->shipmentLabel->purchase['tracking_url_provider']) &&
      $environment == 'Production') {
      return str_replace('http:', 'https:', $this->shipmentLabel->purchase['tracking_url_provider']);
    }
    else {
      return $this->shipmentLabel->purchase['tracking_url_provider'];
    }
  }

  /**
   * Gets a list of all rates for this parcel and addresses.
   *
   * For international shipments, we need the name of the 'signer' who is
   * certifying the contents of the shipment. It should be the name of the
   * current user.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function getRates(): void {
    $this->clearMessages();
    $saleID = $this->shipmentLabel->sales_id;
    $customsSigner = CRM_Core_Session::singleton()->getLoggedInContactID();
    $displayName = CRM_Contact_BAO_Contact::displayName($customsSigner);

    $parcel = CRM_Inventory_BAO_InventorySales::parcel($saleID);
    try {
      $shippoParams = [
        'address_from' => $this->getAddressFrom(),
        'address_to' => $this->getAddressTo(),
        'parcels' => $parcel,
        'customs_declaration' => $this->createCustomsDeclaration($saleID, $customsSigner),
        'meta_data' => "Contact ID {$customsSigner}, name {$displayName}",
        'async' => FALSE,
      ];
      try {
        $response = CRM_Shippo_Connect::shipment($shippoParams);
        $result = json_decode(json_encode($response), TRUE);
      }
      catch (Exception $ex) {
        throw new Exception($ex->getMessage());
      }
      $this->shipmentLabel->shipment = $result;
      foreach ($this->shipmentLabel->shipment['messages'] as &$message) {
        if ($this->isErrorMessage($message)) {
          if ($this->shipmentLabel->shipment['status'] == 'SUCCESS') {
            $message['type'] = 'warning';
          }
          else {
            $message['type'] = 'fatal';
          }
        }
      }

      $this->updateFromShipment($this->shipmentLabel->shipment);
    }
    catch (Exception $exc) {
      $this->addError($exc->getMessage(), 'shipment');
    }
    finally {
      // Array data on shipment and purchase column converted in to json using
      // addListener.
      $this->shipmentLabel->save();
    }
  }

  /**
   * Function to pay for label.
   *
   * Buys the label, charges the account for the shipping.
   *
   * @param string|null $rateId
   *   Rate ID.
   *
   * @return void
   *   Nothing.
   */
  public function pay(?string $rateId = NULL): void {
    try {
      // $this->clearMessages();
      $this->shipmentLabel->purchase = [];
      $this->shipmentLabel->purchase['messages'] = [];
      $rateId = $rateId ?: $this->shipmentLabel->rate_id;
      $param = [
        'rate' => $rateId,
        'label_file_type' => 'PNG',
        'async' => FALSE,
      ];
      $response = CRM_Shippo_Connect::transactions($param);
      $result = json_decode(json_encode($response), TRUE);
      $this->shipmentLabel->purchase = $result;
      $this->updateFromTransaction($this->shipmentLabel->purchase);
      $this->attachLabelImage($this->shipmentLabel->purchase);
    }
    catch (Exception $exc) {
      $this->addError($exc->getMessage());
    }
    finally {
      // Array data on shipment and purchase column converted in to json using
      // addListener.
      $this->shipmentLabel->save();
    }
  }

  /**
   * Function to refund label fee.
   *
   * If the label has not been used, we can take it back.
   *
   * @return void
   *   Nothing.
   */
  public function refund(): void {
    // $this->clearMessages();
    if (!$this->shipmentLabel->is_paid) {
      return;
    }
    try {
      if (empty($this->shipmentLabel->purchase['object_id'])) {
        throw new Exception('Purchase object ID is required for refund.');
      }
      $param = [
        'transaction' => $this->shipmentLabel->purchase['object_id'],
        'async' => FALSE,
      ];
      $response = CRM_Shippo_Connect::refund($param);
      $refund = json_decode(json_encode($response), TRUE);
      if (empty($refund)) {
        $this->addError('Empty response. Maybe label doesn\'t exist?');
      }
      elseif ($refund['status'] == 'SUCCESS' || $refund['status'] == 'PENDING') {
        $this->shipmentLabel->purchase = [];
        $this->shipmentLabel->is_paid = FALSE;
        $this->shipmentLabel->provider = '';
        $this->shipmentLabel->amount = 0;
        $this->shipmentLabel->resource_id = '';
        $this->shipmentLabel->tracking_id = '';
        // Remove the shipping label.
        $this->deleteLabelImage();
      }
      else {
        $this->addError('Unable to complete refund (' . $refund['status'] . ')');
      }
    }
    catch (Exception $exc) {
      $this->addError($exc->getMessage());
    }
    finally {
      // Array data on shipment and purchase column converted in to json using
      // addListener.
      $this->shipmentLabel->save();
    }
  }

  /**
   * Get Best Rate.
   *
   * RATE GUESSING.
   *
   * Fallback rule:
   *    choose the cheapest ups
   * PO BOX rule:
   *    Choose cheapest usps if po box
   * International rule:
   *    Choose cheapest usps if international
   * Faster rule:
   *    Choose next cheapest ups if it is 2 days faster and not more than $2
   * more.
   * Envelope rule:
   *    Choose cheapest usps if cheapest ups is $5 more & weight < 1 pound.
   *
   * This does not always choose the cheapest, if there is another rate that
   * is slightly more but much faster.
   *
   * @param array $shipment
   *   Shipment details.
   *
   * @return mixed|null
   *   Returns the best shipping rates for the parcel and address.
   *
   * @throws Exception
   */
  public function getBestRate(array $shipment): mixed {
    if (empty($shipment) || empty($shipment['rates'])) {
      return NULL;
    }
    if (!is_array(self::SHIPPO_CARRIERS) ||
      !is_array(self::SHIPPO_PREFERRED_INTERNATIONAL_CARRIERS) ||
      !is_array(self::SHIPPO_PREFERRED_DOMESTIC_CARRIERS)) {
      throw new Exception('Setting SHIPPO_CARRIERS is misconfigured');
    }

    $rates = ['all' => []];

    // Rate by Cost.
    usort($shipment['rates'], function ($item, $compare) {
      return $item['amount'] >= $compare['amount'];
    });

    $ratesByCost = $shipment['rates'];
    foreach ($ratesByCost as $rate) {
      foreach (self::SHIPPO_CARRIERS as $carrier) {
        if (!isset($rates[$carrier])) {
          $rates[$carrier] = [];
        }
        $rates['all'][] = $rate;
        if ($rate['provider'] == $carrier) {
          $rates[$carrier][] = $rate;
        }
      }
    }
    $rate = $rates[self::SHIPPO_PREFERRED_DOMESTIC_CARRIERS[0]][0] ?? $rates['all'][0];
    if (empty($rate)) {
      $this->addError('No rates available for shipping providers ' . implode(', ', self::SHIPPO_CARRIERS) . '. Try again later.', 'shipment');
      $rate = NULL;
    }
    elseif (count($rates['all']) == 1) {
      $rate = $rates['all'][0];
    }
    elseif (!$this->isDomestic($shipment['address_from'], $shipment['address_to'])) {
      $rate = $rates[self::SHIPPO_PREFERRED_INTERNATIONAL_CARRIERS[0]][0];
    }
    elseif ($this->isPoBox($shipment['address_to']) && !empty($rates['USPS'])) {
      $rate = $rates['USPS'][0];
    }
    elseif ($smallAndCheap = $this->smallAndCheap($rates, $rate, $shipment)) {
      $rate = $smallAndCheap;
    }
    elseif ($faster = $this->twoDaysFaster($rates, $rate)) {
      $rate = $faster;
    }

    return $rate ?? [];
  }

  /**
   * Function keep error.
   *
   * @param string $msg
   *   Message.
   * @param string $storage
   *   Storage value.
   *
   * @return void
   *   Nothing.
   */
  private function addError(string $msg, string $storage = 'purchase'): void {
    if ($storage == 'shipment') {
      $this->shipmentLabel->shipment['messages'] = $this->shipmentLabel->shipment['messages'] ?? [];
      $this->shipmentLabel->shipment['messages'][] = ['text' => (string) $msg, 'code' => 'error'];
    }
    else {
      $this->shipmentLabel->purchase['messages'] = $this->shipmentLabel->purchase['messages'] ?? [];
      $this->shipmentLabel->purchase['messages'][] = ['text' => (string) $msg, 'code' => 'error'];
    }

    $this->has_error = TRUE;
  }

  /**
   * Clear message for label before generating request.
   *
   * @return void
   *   Nothing.
   */
  private function clearMessages(): void {
    if (!is_array($this->shipmentLabel->shipment)) {
      $this->shipmentLabel->shipment = [];
      $this->shipmentLabel->shipment['messages'] = [];
    }
    else {
      $this->shipmentLabel->shipment['messages'] = [];
    }
    if (!is_array($this->shipmentLabel->purchase)) {
      $this->shipmentLabel->purchase = [];
      $this->shipmentLabel->purchase['messages'] = [];
    }
    else {
      $this->shipmentLabel->purchase['messages'] = [];
    }
  }

  /**
   * Check error message.
   *
   * @param array $msg
   *   Message.
   *
   * @return bool
   *   IsError.
   */
  public function isErrorMessage(array $msg): bool {
    if (array_key_exists('type', $msg) && $msg['type'] == 'warning') {
      return FALSE;
    }
    elseif (array_key_exists('type', $msg) && $msg['type'] == 'fatal') {
      return TRUE;
    }
    else {
      return preg_match('/(error|SubmissionDateTooOld)/', $msg['code']) ||
        preg_match('/(must|invalid|warning|not responding|hard:)/i', $msg['text']);
    }
  }

  /**
   * Get Rate.
   *
   * Given a rate id, return the rate hash from the list of possible rates
   * for this parcel and address.
   *
   * @param array $rateInfo
   *   Rate info (TransactionRate).
   *
   * @return array|mixed
   *   Rate info.
   */
  private function getRate(array $rateInfo): mixed {
    if (!empty($rateInfo['object_id'])) {
      foreach ($this->shipmentLabel->shipment['rates'] as $rate) {
        if ($rate['object_id'] == $rateInfo['object_id']) {
          return $rate;
        }
      }
    }
    return [];
  }

  /**
   * Get From address from shippo.
   *
   * @return array
   *   From address.
   */
  private function getAddressFrom(): array {
    return CRM_Shippo_Utils::getFromAddress();
  }

  /**
   * Get Address for contact.
   *
   * @return array
   *   Address.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private function getAddressTo(): array {
    if (is_null($this->sales->contact_id)) {
      throw new Exception('Order does not have a member');
    }
    $address = $this->address ?? [];
    if (is_null(empty($address))) {
      throw new Exception('Member does not have an address');
    }
    if (empty($address['name'])) {
      $displayName = CRM_Contact_BAO_Contact::displayName($this->sales->contact_id);
      $address['name'] = $displayName;
    }
    $fromAddress = CRM_Shippo_Utils::getFromAddress();
    if ($address['country'] == $fromAddress['country']) {
      return $address;
    }
    else {
      return array_merge($address, ['phone' => $fromAddress['phone']]);
    }
  }

  /**
   * Function to check street address.
   *
   * @param array $address
   *   Address.
   *
   * @return bool
   *   Is Po Box.
   */
  private function isPoBox(array $address): bool {
    if ($address) {
      $addy = trim($address['street1']);
      return preg_match('/^p\s*o\s*box/i', $addy) || preg_match('/^p\s*o\s*#?\d+/i', $addy);
    }
    else {
      return FALSE;
    }
  }

  /**
   * Is Domestic Shipment.
   *
   * A shipment is domestic if the country code is the same. For the US we
   * only treat the 50 states plus DC as domestic (because that is how
   * carriers handle it). US territories, US Virgin Islands, and Puerto Rico
   * are all considered international (except for the purposes of customs
   * declaration).
   *
   * @param array $from
   *   From Address.
   * @param array $to
   *   To Address.
   *
   * @return bool
   *   Is domestic.
   */
  private function isDomestic(array $from, array $to): bool {
    if ($from && $to) {
      $fromCountry = $from['country'];
      $toCountry = $to['country'];
      if ($fromCountry == $toCountry) {
        if ($fromCountry == 'US' && !empty($to['state']) && !in_array($to['state'], CRM_Inventory_Utils::US_STATES)) {
          return FALSE;
        }
        else {
          return TRUE;
        }
      }
      else {
        return FALSE;
      }
    }
    else {
      return TRUE;
    }
  }

  /**
   * Get Two day Faster rate.
   *
   * If the second-cheapest rate is 2 days faster, choose that one if it is
   * under $2 more.
   *
   * @param array $rates
   *   Rate details.
   * @param array $defaultRate
   *   Default Rate.
   *
   * @return mixed
   *   Rate.
   */
  private function twoDaysFaster(array $rates, array $defaultRate): mixed {
    $provider = $defaultRate['provider'];
    if (isset($rates[$provider][1]) &&
      $defaultRate['estimated_days'] > $rates[$provider][1]['estimated_days'] + 2 &&
      $defaultRate['amount'] > $rates[$provider][1]['amount'] - 2) {
      return $rates[$provider][1];
    }
    else {
      return NULL;
    }
  }

  /**
   * Get Cheap rate.
   *
   *  Choose cheapest USPS if cheapest ups is $5 more & weight < 1 pound.
   *
   * @param array $rates
   *   Rate details.
   * @param array $defaultRate
   *   Default rate details.
   * @param array $shipment
   *   Shipment details.
   *
   * @return mixed
   *   Rate.
   */
  private function smallAndCheap(array $rates, array $defaultRate, array $shipment): mixed {
    if ($shipment['parcels'][0]['mass_unit'] == 'lb' && $shipment['parcels'][0]['weight'] <= 1) {
      if (in_array('UPS', self::SHIPPO_CARRIERS) && in_array('USPS', self::SHIPPO_CARRIERS)) {
        $uspsRate = $rates['USPS'][0];
        if ($defaultRate['amount'] > $uspsRate['amount'] + 3) {
          return $uspsRate;
        }
      }
    }
    return NULL;
  }

  /**
   * Custom Declaration.
   *
   * @param array $from
   *   From Address.
   * @param array $to
   *   To Address.
   *
   * @return bool
   *   Is declaration required.
   */
  private function customsDeclarationRequired(array $from, array $to): bool {
    $fromCountry = $from['country'];
    $toCountry = $to['country'];
    if ($fromCountry == 'USA' && $toCountry == 'USA') {
      return FALSE;
    }
    else {
      return !$this->isDomestic($from, $to);
    }
  }

  /**
   * Returns Shippo parcel dimensions specification.
   *
   * For example:
   * {length: 8, width: 6, height: 4, distance_unit: "in", weight: 1,
   * mass_unit: "lb"}
   *
   * @return array
   *   Parcel Dimensions.
   *
   * @throws Exception
   */
  private function getDimensions($product) {
    $parcelDimensions = $this->product;
    if (is_null($parcelDimensions)) {
      throw new Exception('Could not guess the parcel dimensions');
    }
    return $parcelDimensions;
  }

  /**
   * Get Custom details for item.
   *
   * @param int $saleID
   *   Sale ID.
   *
   * @return array
   *   Item details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getItemsForCustoms(int $saleID): array {
    $lineItems = CRM_Inventory_BAO_InventorySales::getProductDetailOfLineItem($saleID);
    $items = [];
    foreach ($lineItems as $li) {
      if ($li['is_shipping_required']) {
        if ($li['line_total'] < 1) {
          $li['line_total'] = 1;
        }
        $items[] = [
          'description' => $li['item'],
          'quantity' => $li['qty'],
          'net_weight' => ($li['qty'] * $li['packed_weight']),
          'mass_unit' => $li['mass_unit'],
          'value_amount' => $li['line_total'],
          'value_currency' => 'USD',
          'origin_country' => 'USA',
        ];
      }
    }
    return $items;
  }

  /**
   * Create Custom Declaration.
   *
   * @param int $saleID
   *   Sale ID.
   * @param string|null $signer
   *   Signer Contact.
   *
   * @return array|null
   *   Customs Declaration details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private function createCustomsDeclaration(int $saleID, ?string $signer = NULL): ?array {
    if (empty($this->address)) {
      return NULL;
    }
    $fromAddress = CRM_Shippo_Utils::getFromAddress();
    $to = [
      'country' => $this->address['country'],
      'state' => $this->address['state'],
    ];
    $from = [
      'country' => $fromAddress['country'],
      'state' => $fromAddress['state'],
    ];
    if ($this->customsDeclarationRequired($to, $from)) {
      $params = [
        'contents_type' => 'GIFT',
        'contents_explanation' => 'Membership Premiums',
        'non_delivery_option' => 'RETURN',
        'certify' => TRUE,
        'certify_signer' => $signer,
        'items' => $this->getItemsForCustoms($saleID),
      ];
      /** @var OpenAPI\Client\Model\CustomsDeclaration $result */
      $result = CRM_Shippo_Connect::declarations($params);
      return json_decode(json_encode($result), TRUE);
    }
    else {
      return NULL;
    }
  }

  /**
   * Update Shipment details.
   *
   * @param array $shipment
   *   Shipment details.
   *
   * @return void
   *   Nothing.
   *
   * @throws Exception
   */
  private function updateFromShipment(array $shipment): void {
    if ($shipment['status'] == 'SUCCESS') {
      $this->shipmentLabel->is_valid = TRUE;
      $this->shipmentLabel->has_error = FALSE;
    }
    else {
      $this->shipmentLabel->has_error = TRUE;
    }
    $rate = $this->getBestRate($shipment);
    if (!empty($rate)) {
      $this->shipmentLabel->rate_id = $rate['object_id'];
      $this->shipmentLabel->amount = $rate['amount'];
      $this->shipmentLabel->currency = $rate['currency'];
      $this->shipmentLabel->provider = $rate['provider'];
    }
    foreach ($shipment['messages'] as $message) {
      if ($this->isErrorMessage($message)) {
        $this->shipmentLabel->is_valid = FALSE;
        $this->shipmentLabel->has_error = TRUE;
      }
    }
  }

  /**
   * Update various fields based on the Shipment object returned from Shippo.
   *
   * @param array $transaction
   *   Transaction details.
   *
   * @return void
   *   Nothing.
   */
  private function updateFromTransaction(array $transaction): void {
    $rate = $this->getRate($transaction['rate']);
    $this->shipmentLabel->is_paid = $transaction['status'] == 'SUCCESS';
    if (!empty($rate)) {
      $this->rate_id = $transaction['rate'];
    }
    $this->shipmentLabel->resource_id = $transaction['object_id'];
    $this->shipmentLabel->tracking_id = $transaction['tracking_number'];
    $this->shipmentLabel->tracking_url = $transaction['tracking_url_provider'];
    if (!empty($rate['provider'])) {
      $this->shipmentLabel->provider = $rate['provider'];
    }
    if (!empty($rate['amount'])) {
      $this->shipmentLabel->amount = $rate['amount'];
    }
    if (!empty($rate['currency'])) {
      $this->shipmentLabel->currency = $rate['currency'];
    }
    if ($transaction['status'] != 'SUCCESS') {
      $this->addError('Unable to complete purchase');
    }
    else {
      $this->shipmentLabel->has_error = FALSE;
    }
  }

  /**
   * Downloads the image from Shippo and saves it to active_storage.
   *
   * @param array $purchase
   *   Payment details.
   *
   * @return void
   *   Nothing.
   */
  private function attachLabelImage(array $purchase): void {
    try {
      if (!empty($purchase['label_url'])) {
        $path = CRM_Core_Config::singleton()->customFileUploadDir . '/' .
          $purchase['object_id'] . '.png';
        if (copy($purchase['label_url'], $path)) {
          $this->shipmentLabel->label_url = $purchase['object_id'] . '.png';
        }
        else {
          if ($img = file_get_contents($purchase['label_url'])) {
            file_put_contents($img, $path);
            $this->shipmentLabel->label_url = $purchase['object_id'] . '.png';
          }
        }
      }
    }
    catch (\Exception $e) {
    }
  }

  /**
   * Delete shipping label file from civicrm.
   *
   * @return void
   *   Nothing.
   */
  private function deleteLabelImage(): void {
    try {
      if (!empty($this->shipmentLabel->label_url)) {
        $path = CRM_Core_Config::singleton()->customFileUploadDir . '/' . $this->shipmentLabel->label_url;
        if (file_exists($path)) {
          @unlink($path);
          $this->shipmentLabel->label_url = '';
          $this->shipmentLabel->tracking_url = '';
        }
      }
    }
    catch (\Exception $e) {
    }
  }

  /**
   * Print label.
   *
   * @param int $shipmentID
   *
   * @param string $type
   *
   * @return void
   */
  public static function printLabels(int $shipmentID, string $type): void {
    try {
      if ($type == 'print_2') {
        $layout = 2;
      }
      else {
        $layout = 4;
      }
      $labels = self::getLabelsForShipment($shipmentID);
      // Echo '<pre>'; print_r($labels); echo '</pre>';exit;.
      $htmlArray = [];
      if (!empty($labels)) {
        $arrayChunk = array_chunk($labels, $layout);
        if ($layout == 4) {
          foreach ($arrayChunk as $chunk) {
            $html = '';
            $html .= '<div class="four-up-container">' . PHP_EOL;
            foreach (array_chunk($chunk, 2) as $labels_this_page) {
              $html .= '<div class="four-up-row">' . PHP_EOL;
              foreach ($labels_this_page as $item) {
                $html .= '<div class="four-up-cell img-container">' . PHP_EOL;
                $html .= '<img class = "four-up-image img" style="' . $item['label_style'] . '" src="' . $item['label_image'] . '" />' . PHP_EOL;
                $html .= '</div>' . PHP_EOL;
              }
              $html .= '</div>' . PHP_EOL;
            }
            $html .= '</div>' . PHP_EOL;
            $htmlArray[] = $html;
          }
        }
        else {
          foreach ($arrayChunk as $labels_this_page) {
            $html = '';
            $html .= '<div class="two-up-container">' . PHP_EOL;
            $html .= '<div class="two-up-row">' . PHP_EOL;
            foreach ($labels_this_page as $item) {
              $html .= '<div class="two-up-cell">' . PHP_EOL;
              $html .= '<img class = "two-up-image" src="' . $item['label_image'] . '" />' . PHP_EOL;
              $html .= '</div>' . PHP_EOL;
            }
            $html .= '</div>' . PHP_EOL;

            $html .= '</div>' . PHP_EOL;
            $htmlArray[] = $html;
          }
        }
      }
      if (!empty($htmlArray)) {
        $pdfFormat = [
          'paper_size' => 'A4',
          'stationery' => NULL,
          'orientation' => 'portrait',
          'metric' => 'in',
          'margin_top' => 0.25,
          'margin_bottom' => 0.25,
          'margin_left' => 0.25,
          'margin_right' => 0.25,
          'weight' => 2,
        ];
        // page_size: "Letter", orientation: orientation,
        // margin: {top: "0.25in", left: "0.25in", bottom: "0.25in", right: "0.25in"}.
        $customCssPath = CRM_Extension_System::singleton()->getMapper()->keyToBasePath('com.skvare.inventory') . '/assets/css/label.css';
        $customCss = file_get_contents($customCssPath);
        CRM_Core_Region::instance('export-document-header')->add(['style' => "{$customCss}"]);
        CRM_Utils_PDF_Utils::html2pdf($htmlArray, "Shipment_Labels_{$shipmentID}.pdf", FALSE, $pdfFormat);
        CRM_Utils_System::civiExit();
      }

      // print_r($html); exit;.
    }
    catch (UnauthorizedException $e) {
    }
    catch (CRM_Core_Exception $e) {
    }
  }

  /**
   * Get shipment label for shipment id.
   *
   * @param int $shipmentID
   *   Shipment ID.
   *
   * @return array
   *   Labels details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getLabelsForShipment(int $shipmentID): array {
    $inventoryShipmentLabels = InventoryShipmentLabels::get(TRUE)
      ->addSelect('id', 'is_valid', 'is_paid', 'label_url', 'tracking_url')
      ->addJoin('InventorySales AS inventory_sales', 'INNER')
      ->addWhere('is_paid', '=', TRUE)
      ->addWhere('label_url', 'IS NOT EMPTY')
      ->addWhere('is_valid', '=', TRUE)
      ->addWhere('inventory_sales.shipment_id', '=', $shipmentID)
      ->setLimit(0)
      ->execute();
    $shipmentLabels = [];
    foreach ($inventoryShipmentLabels as $label) {
      if ($label['label_url']) {
        $path = CRM_Core_Config::singleton()->customFileUploadDir . '/' . $label['label_url'];
        if (file_exists($path)) {
          $image = CRM_Inventory_Utils::imageEncodeBase64($path);
          $label['label_image'] = $image;
          $label['label_style'] = CRM_Inventory_Utils::longestSideVertical($path);
          $shipmentLabels[] = $label;
        }
      }
    }
    return $shipmentLabels;
  }

}
