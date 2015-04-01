<?php
$extensionClassesPath = t3lib_extMgm::extPath('gridelements_fce') . 'Classes/';

return array(
  'tx_gridelements_drawitemhook' => $extensionClassesPath . 'Hooks/DrawItem.php',
  'tx_gridelementsfce_cobjhook'     => $extensionClassesPath . 'Hooks/CObj.php',
  'tx_gridelementsfce_getdatahook'  => $extensionClassesPath . 'Hooks/GetData.php',
);