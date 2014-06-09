<?php
namespace Inouit\gridelementsFce\Hooks;

/**
 * This Hook is heavely based on the wec_contentelements hook. I've just implemented 6.2 compatibility and change some varriables name for, in my humble opinion, easiest understanding
 */
class CObj implements \TYPO3\CMS\Frontend\ContentObject\ContentObjectGetSingleHookInterface {

  protected $cObj;
  protected $pageRenderer;

  /**
   * Renders a single cObject, returning its output.
   *
   * @param string    $contentObjectName: The name of the cObject.
   * @param array   $configuration: The Typoscript configuration.
   * @param string    $TypoScriptKey: The key assigned to the cObject.
   * @param tslib_ccObj $parentObject: Back reference to parent cObject.
   * @return  string
   */
  public function getSingleContentObject($contentObjectName, array $configuration, $TypoScriptKey, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
    $this->cObj =& $parentObject;
    $this->pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
    switch($contentObjectName) {
      case 'FLEXFORM_SECTION':
        $content = $this->FLEXFORM_SECTION($configuration);
        break;
      case 'INCLUDE_CSS':
        $content = $this->INCLUDE_CSS($configuration);
        break;
      case 'CSS_INLINE':
        $content = $this->CSS_INLINE($configuration);
        break;
      case 'INCLUDE_JS':
        $content = $this->INCLUDE_JS($configuration);
        break;
      case 'INCLUDE_JS_FOOTER':
        $content = $this->INCLUDE_JS_FOOTER($configuration);
        break;
    }

    return $content;
  }



  /**
   * Iterates over a flexform section, returning the combined output of all
   * elements within the specified section.
   *
   * @param array $conf: The TypoScript configuration.
   * @return  string
   *
   */
  public function FLEXFORM_SECTION(array $conf) {
    $sectionArray = $this->cObj->getData($conf['rootPath'], $this->cObj->data);
    $content = '';
    if ($this->cObj->checkIf($conf['if.'])) {
      $counter = 1;
      foreach ($sectionArray as $index => $section) {
        $GLOBALS['TSFE']->register['FFSECTION_COUNTER'] = $counter++;
        $this->cObj->sectionRootPath = $conf['rootPath'] . '/' . $index;
        $content .= $this->cObj->cObjGet($conf);
      }

      if ($conf['wrap']) {
        $content = $this->cObj->wrap($content, $conf['wrap']);
      }
      if ($conf['stdWrap.']) {
        $content = $this->cObj->stdWrap($content, $conf['stdWrap.']);
      }
    }

    return $content;
  }

  /**
   * Iterates over a CSS inclusion
   * @param array $conf : TS configuration
   * @return  string
   */
  public function INCLUDE_CSS(array $conf) {
    if($conf && count($conf)) {
      foreach ($conf as $key => $CSSfile) {
        $cssFileConfig = &$conf[$key . '.'];
        if (isset($cssFileConfig['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($cssFileConfig['if.'])) {
          continue;
        }
        $ss = $cssFileConfig['external'] ? $CSSfile : $GLOBALS['TSFE']->tmpl->getFileName($CSSfile);
        if ($ss) {
          if ($cssFileConfig['import']) {
            if (!$cssFileConfig['external'] && $ss[0] !== '/') {
              // To fix MSIE 6 that cannot handle these as relative paths (according to Ben v Ende)
              $ss = TYPO3\CMS\Core\Utility\GeneralUtility::dirname(GeneralUtility::getIndpEnv('SCRIPT_NAME')) . '/' . $ss;
            }
            $this->pageRenderer->addCssInlineBlock('import_' . $key, '@import url("' . htmlspecialchars($ss) . '") ' . htmlspecialchars($cssFileConfig['media']) . ';', empty($cssFileConfig['disableCompression']), $cssFileConfig['forceOnTop'] ? TRUE : FALSE, '');
          } else {
            $this->pageRenderer->addCssFile(
              $ss,
              $cssFileConfig['alternate'] ? 'alternate stylesheet' : 'stylesheet',
              $cssFileConfig['media'] ?: 'all',
              $cssFileConfig['title'] ?: '',
              empty($cssFileConfig['disableCompression']),
              $cssFileConfig['forceOnTop'] ? TRUE : FALSE,
              $cssFileConfig['allWrap'],
              $cssFileConfig['excludeFromConcatenation'] ? TRUE : FALSE,
              $cssFileConfig['allWrap.']['splitChar']
            );
            unset($cssFileConfig);
          }
        }
      }
    }
  }

  /**
   * Inline CSS inclusion
   * @param array $conf : TS configuration
   * @return  string
   */
  public function CSS_INLINE(array $conf) {
    if($conf && count($conf)) {
      $style = $GLOBALS['TSFE']->cObj->cObjGet($conf);

      if (trim($style)) {
        $this->pageRenderer->addCssInlineBlock('additionalTSFEInlineStyle', $style);
      }
    }
  }

  /**
   * Iterates over a Js inclusion on header
   * @param array $conf : TS configuration
   * @return  string
   */
  public function INCLUDE_JS(array $conf) {
    if($conf && count($conf)) {
      foreach ($conf as $key => $JSfile) {
        if (!is_array($JSfile)) {
          if (isset($conf[$key . '.']['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($conf[($key . '.')]['if.'])) {
            continue;
          }
          $ss = $conf[$key . '.']['external'] ? $JSfile : $GLOBALS['TSFE']->tmpl->getFileName($JSfile);
          if ($ss) {
            $jsConfig = &$conf[$key . '.'];
            $type = $jsConfig['type'];
            if (!$type) {
              $type = 'text/javascript';
            }
            $this->pageRenderer->addJsFile(
              $ss,
              $type,
              empty($jsConfig['disableCompression']),
              $jsConfig['forceOnTop'] ? TRUE : FALSE,
              $jsConfig['allWrap'],
              $jsConfig['excludeFromConcatenation'] ? TRUE : FALSE,
              $jsConfig['allWrap.']['splitChar']
            );
            unset($jsConfig);
          }
        }
      }
    }
  }

  /**
   * Iterates over a Js inclusion on footer
   * @param array $conf : TS configuration
   * @return  string
   */
  public function INCLUDE_JS_FOOTER(array $conf) {
    if($conf && count($conf)) {
      foreach ($conf as $key => $JSfile) {
        if (isset($conf[$key . '.']['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($conf[($key . '.')]['if.'])) {
          continue;
        }
        $ss = $conf[$key . '.']['external'] ? $JSfile : $GLOBALS['TSFE']->tmpl->getFileName($JSfile);
        if ($ss) {
          $jsConfig = &$conf[$key . '.'];
          $type = $jsConfig['type'];
          if (!$type) {
            $type = 'text/javascript';
          }
          $this->pageRenderer->addJsFooterFile(
            $ss,
            $type,
            empty($jsConfig['disableCompression']),
            $jsConfig['forceOnTop'] ? TRUE : FALSE,
            $jsConfig['allWrap'],
            $jsConfig['excludeFromConcatenation'] ? TRUE : FALSE,
            $jsConfig['allWrap.']['splitChar']
          );
          unset($jsConfig);
        }
      }
    }
  }
}