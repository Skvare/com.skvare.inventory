<?php

return [
  [
    'name' => 'inventory_membership_referral.activity.create',
    'entity' => 'OptionValue',
    'params' => [
      'option_group_id' => 'activity_type',
      'name' => 'Referral-Membership Extended',
      'description' => 'Membership Extended due to use of referral code.',
      'component_id' => 'CiviMember',
      'is_active' => 1,
    ],
  ],
];
