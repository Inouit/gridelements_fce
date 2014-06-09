<?php
	$_EXTCONF = unserialize($_EXTCONF);

  // Hooks
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['GridElementsTeam\Gridelements\Hooks\DrawItem'] = array(
    'className' => 'Inouit\gridelementsFce\Hooks\DrawItem',
  );
  $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClassDefault'][] = 'Inouit\gridelementsFce\Hooks\CObj';
  $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['getData'][] = 'Inouit\gridelementsFce\Hooks\GetData';
?>