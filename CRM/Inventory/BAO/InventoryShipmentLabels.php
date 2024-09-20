<?php

/**
 *
 */

use Civi\Api4\InventoryShipmentLabels;

/**
 *
 */
class CRM_Inventory_BAO_InventoryShipmentLabels extends CRM_Inventory_DAO_InventoryShipmentLabels {

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryShipmentLabels
   */
  private $shipmentLabel = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventorySales
   */
  private $sales  = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryProduct
   */
  private $product = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryProductVariant
   */
  private $productVariant = NULL;

  /**
   * Object.
   *
   * @var array
   */
  private $address = NULL;

  const  shippo_carriers = ["UPS", "USPS"];
  const shippo_preferred_domestic_carriers = ["UPS"];
  const shippo_preferred_international_carriers = ["USPS"];

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
  public function boot(string $columName, mixed $value) {
    $this->shipmentLabel = self::findById($columName, $value, TRUE);
    if ($this->shipmentLabel->sales_id) {
      $this->sales = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventorySales', ['id' => $this->shipmentLabel->sales_id], TRUE);
      if ($this->sales->id) {
        $this->productVariant = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventoryProductVariant', ['sale_id' => $this->sales->id], TRUE);
        if ($this->productVariant->id) {
          $this->product = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventoryProduct', ['id' => $this->productVariant->product_id], TRUE);
        }
        if ($this->productVariant->contact_id) {
          $this->address = CRM_Inventory_Utils::getAddress($this->productVariant->contact_id);
        }
      }
    }
  }
  public function saleProductVariantes() {
    if ($this->productVariant === NULL){
      $this->productVariant = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventoryProductVariant', ['sale_id' => $this->sales->id], TRUE);
    }
    return $this->productVariant;
  }

  public function sales() {
    if ($this->sales === NULL){
      $this->sales = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventorySales', ['id' => $this->shipmentLabel->sales_id], TRUE);
    }
    return $this->sales;
  }

  public function sales2() {
    if ($this->sales === NULL) {
      $this->sales = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventorySales', ['id' => $this->shipmentLabel->sales_id], TRUE);
    }
    return $this->sales;
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
  public static function findById($searchColumn = 'id', int|string $searchValue = '', bool $returnObject = FALSE) {
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
   *
   */
  public function asyncGetRatesAndPay($signer) {
    if (!$this->shipmentLabel->is_valid || $this->shipmentExpired()) {
      $this->getRates($signer);
    }
    if (!$this->shipmentLabel->is_paid && $this->shipmentLabel->is_valid && $this->shipmentLabel->rate_id) {
      $this->pay();
    }
    if ($this->shipmentLabel->has_error) {
      throw new Exception($this->errorMessages());
    }
  }

  /**
   *
   */
  public function asyncRefund() {
    if ($this->shipmentLabel->is_paid) {
      $this->refund();
    }
    if ($this->shipmentLabel->has_error) {
      throw new Exception($this->errorMessages());
    }
  }

  /**
   *
   */
  public function readyToPay() {
    return $this->hasRates() && !$this->shipmentLabel->is_paid && !is_null($this->shipmentLabel->rate_id);
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
  public function trackingUrl() {
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
   * Get Rates.
   *
   * @param $customsSigner
   *
   * @return void
   */
  public function getRates($customsSigner = NULL) {
    $this->clearMessages();
    try {
      $response = Http::post('https://api.goshippo.com/shipments', [
        'address_from' => $this->getAddressFrom(),
        'address_to' => $this->getAddressTo(),
        'parcels' => $this->getDimensions(),
        'customs_declaration' => $this->createCustomsDeclaration($customsSigner),
        'async' => FALSE,
      ]);
      $this->shipmentLabel->shipment = $response->json();
      foreach ($this->shipmentLabel->shipment['messages'] as $message) {
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
      $this->addError($exc, 'shipment');
    }
    finally {
      $this->shipmentLabel->save();
    }
  }

  /**
   * Function to pay for label.
   *
   * @param string $rateId
   *   Rate ID.
   *
   * @return void
   *   Nothing.
   */
  public function pay(string $rateId = NULL): void {
    try {
      $this->clearMessages();
      $rateId = $rateId ?: $this->shipmentLabel->rate_id;
      $response = Http::post('https://api.goshippo.com/transactions', [
        'rate' => $rateId,
        'label_file_type' => 'PNG',
        'async' => FALSE,
      ]);
      $this->shipmentLabel->purchase = $response->json();
      $this->updateFromTransaction($this->shipmentLabel->purchase);
      $this->attachLabelImage($this->shipmentLabel->purchase);
    }
    catch (Exception $exc) {
      $this->addError($exc);
    }
    finally {
      $this->save();
    }
  }

  /**
   * Function to refund label fee.
   *
   * @return void
   */
  public function refund(): void {
    $this->clearMessages();
    if (!$this->shipmentLabel->is_paid) {
      return;
    }
    try {
      $response = Http::post('https://api.goshippo.com/refunds', [
        'transaction' => $this->shipmentLabel->purchase['object_id'],
        'async' => FALSE,
      ]);
      $refund = $response->json();
      if (empty($refund)) {
        $this->addError('Empty response. Maybe label doesn\'t exist?');
      }
      elseif ($refund['status'] == 'SUCCESS' || $refund['status'] == 'PENDING') {
        $this->shipmentLabel->purchase = [];
        $this->shipmentLabel->is_paid = FALSE;
        $this->shipmentLabel->provider = NULL;
        $this->shipmentLabel->amount = 0;
        $this->shipmentLabel->resource_id = NULL;
        $this->shipmentLabel->tracking_id = NULL;
        // $this->image->purge();
      }
      else {
        $this->addError('Unable to complete refund (' . $refund['status'] . ')');
      }
    }
    catch (Exception $exc) {
      $this->addError($exc);
    }
    finally {
      $this->shipmentLabel->save();
    }
  }

  /**
   * Get Best Rate.
   *
   * @param $shipment
   *   Shipment details.
   *
   * @return mixed|null
   *   Mixed.
   */
  public function getBestRate($shipment): mixed {
    if (empty($shipment) || empty($shipment['rates'])) {
      return NULL;
    }
    if (!is_array(self::shippo_carriers) ||
      !is_array(self::shippo_preferred_international_carriers) ||
      !is_array(self::shippo_preferred_domestic_carriers)) {
      throw new Exception('Setting shippo_carriers is misconfigured');
    }

    $rates = ['all' => []];
    $ratesByCost = array_map(function ($rate) {
      return $rate['amount'];
    }, $shipment['rates']);
    asort($ratesByCost);
    foreach ($ratesByCost as $rate) {
      foreach (self::shippo_carriers as $carrier) {
        if (!isset($rates[$carrier])) {
          $rates[$carrier] = [];
        }
        $rates['all'][] = $rate;
        if ($rate['provider'] == $carrier) {
          $rates[$carrier][] = $rate;
        }
      }
    }

    $rate = $rates[self::shippo_preferred_domestic_carriers[0]][0] ?? $rates['all'][0];

    if (empty($rate)) {
      $this->addError('No rates available for shipping providers ' . implode(', ', self::shippo_carriers) . '. Try again later.', 'shipment');
      $rate = NULL;
    }
    elseif (count($rates['all']) == 1) {
      $rate = $rates['all'][0];
    }
    elseif (!$this->isDomestic($shipment['address_from'], $shipment['address_to'])) {
      $rate = $rates[self::shippo_preferred_international_carriers[0]][0];
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

    return $rate['object_id'] ?? NULL;
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
   */
  private function addError(string $msg, string $storage = 'purchase'): void {
    $this->shipmentLabel->$storage['messages'] = $this->shipmentLabel->$storage['messages'] ?? [];
    $this->shipmentLabel->$storage['messages'][] = ['text' => (string) $msg, 'code' => 'error'];
    $this->has_error = TRUE;
  }

  /**
   * Clear message for label before generating request.
   *
   * @return void
   *   Nothing.
   */
  private function clearMessages(): void {
    $this->shipmentLabel->shipment['messages'] = [];
    $this->shipmentLabel->purchase['messages'] = [];
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
    if ($msg['type'] == 'warning') {
      return FALSE;
    }
    elseif ($msg['type'] == 'fatal') {
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
   * @param string $rateId
   *   Rate ID.
   *
   * @return array|mixed
   *   Rate info.
   */
  private function getRate(string $rateId): mixed {
    foreach ($this->shipmentLabel->shipment['rates'] as $rate) {
      if ($rate['object_id'] == $rateId) {
        return $rate;
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
   * @param array $from
   *   From Address.
   * @param array $to
   *   To Address.
   *
   * @return bool
   *   Is domestic.
   */
  private function isDomestic($from, $to) {
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
   * Get Cheat rate.
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
      if (in_array('UPS', self::shippo_carriers) && in_array('USPS', self::shippo_carriers)) {
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
  private function getDimensions() {
    $parcelDimensions = $this->order->parcel;
    if (is_null($parcelDimensions)) {
      throw new Exception('Could not guess the parcel dimensions');
    }
    return $parcelDimensions;
  }

  /**
   *
   */
  private function getItemsForCustoms() {
    $items = [];
    foreach ($this->order->orderItems as $oi) {
      if ($oi->isShippingRequired()) {
        $value = $oi->item->priceOrValue();
        if ($value < 1) {
          $value = 1;
        }
        $items[] = [
          'description' => $oi->item->displayName(),
          'quantity' => $oi->quantity,
          'net_weight' => ($oi->quantity * $oi->item->weight),
          'mass_unit' => $oi->item->massUnit(),
          'value_amount' => ($oi->quantity * $value),
          'value_currency' => $oi->item->currency,
          'origin_country' => Conf::shipping_from_country(),
        ];
      }
    }
    return $items;
  }

  /**
   *
   */
  private function createCustomsDeclaration($signer) {
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
      return Http::post('https://api.goshippo.com/customs/declarations', [
        'contents_type' => 'GIFT',
        'contents_explanation' => 'Membership Premiums',
        'non_delivery_option' => 'RETURN',
        'certify' => TRUE,
        'certify_signer' => $signer,
        'items' => $this->getItemsForCustoms(),
      ])->json();
    }
    else {
      return NULL;
    }
  }

  /**
   *
   */
  private function updateFromShipment($shipment) {
    if ($shipment['status'] == 'SUCCESS') {
      $this->shipmentLabel->is_valid = TRUE;
      $this->shipmentLabel->has_error = FALSE;
    }
    else {
      $this->shipmentLabel->has_error = TRUE;
    }
    $this->shipmentLabel->rate_id = $this->getBestRate($shipment);
    foreach ($shipment['messages'] as $message) {
      if ($this->isErrorMessage($message)) {
        $this->shipmentLabel->is_valid = FALSE;
        $this->shipmentLabel->has_error = TRUE;
      }
    }
  }

  /**
   *
   */
  private function updateFromTransaction($transaction) {
    $rate = $this->getRate($transaction['rate']);
    $this->is_paid = $transaction['status'] == 'SUCCESS';
    $this->rate_id = $transaction['rate'];
    $this->resource_id = $transaction['object_id'];
    $this->tracking_id = $transaction['tracking_number'];
    $this->provider = $rate['provider'];
    $this->amount = $rate['amount'];
    $this->currency = $rate['currency'];
    if ($transaction['status'] != 'SUCCESS') {
      $this->addError('Unable to complete purchase');
    }
    else {
      $this->has_error = FALSE;
    }
  }

  /**
   *
   */
  private function attachLabelImage($purchase) {
    if (!empty($purchase['label_url'])) {
      $this->image->purge();
      $this->image->attach(file_get_contents($purchase['label_url']), 'label.png');
    }
  }

}
