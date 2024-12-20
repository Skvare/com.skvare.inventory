{foreach from=$inventoryProductVariantFields key=fieldName item=fieldSpec}
  {assign var=notSetFieldName value=$fieldName|cat:'_notset'}
  <tr>
    <td>
      {include file="CRM/Core/DatePickerRange.tpl" from='_low' to='_high'}
    </td>
    <td>
      &nbsp;{$form.$fieldName.label}&nbsp;&nbsp;{$form.$fieldName.html}
    </td>
  </tr>
{/foreach}
