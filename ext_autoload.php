<?php
$extensionClassesPath = t3lib_extMgm::extPath('gridelements_fce') . 'Classes/';

return array(
  'Inouit\gridelementsFce\Hooks\DrawItem' => $extensionClassesPath . 'Hooks/DrawItem.php',
  'Inouit\gridelementsFce\Hooks\CObj'     => $extensionClassesPath . 'Hooks/CObj.php',
  'Inouit\gridelementsFce\Hooks\GetData'  => $extensionClassesPath . 'Hooks/GetData.php',
);