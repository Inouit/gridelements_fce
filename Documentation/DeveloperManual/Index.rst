.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer-manual:

Developer Manual
================

This chapter describes some hooks which provide you an easy way to use flexform's session in typoscript and an introduction to the gridelements_fce generator.

Section
-------

Sometimes in FCE, it's convenient to loop on some items, in a slideshow for example.

.. figure:: ../Images/DeveloperManual/loop.png
    :width: 681px
    :alt: Loop example in the backend form

In flexform, it looks like this::

    <el>
      <images>
        <section>1</section>
        <type>array</type>
        <el>
          <image>
            <type>array</type>
            <tx_templavoila>
              <title>LLL:EXT:skinFlex/Resources/Private/Language/slideshow.xlf:flexform.slideshow.image</title>
            </tx_templavoila>
            <el>
              <file>
                <TCEforms>
                  <label>LLL:EXT:skinFlex/Resources/Private/Language/slideshow.xlf:flexform.slideshow.image</label>
                  <config>
                    <type>group</type>
                    <internal_type>file</internal_type>
                    <allowed>gif,jpg,jpeg,tif,bmp,pcx,tga,png,pdf,ai</allowed>
                    <max_size>5000</max_size>
                    <uploadfolder>uploads/skinFlex/slideshow/</uploadfolder>
                    <show_thumbs>1</show_thumbs>
                    <maxitems>1</maxitems>
                  </config>
                </TCEforms>
              </file>
              <caption>
                <TCEforms>
                  <label>LLL:EXT:skinFlex/Resources/Private/Language/slideshow.xlf:flexform.slideshow.caption</label>
                  <config>
                    <type>input</type>
                  </config>
                </TCEforms>
              </caption>
            </el>
          </image>
        </el>
      </images>
    </el>



Use this kind of configuration in typoscript may be difficult. You can use `XPATH Content Object <http://typo3.org/extensions/repository/view/cobj_xpath>`_ but this extension provide a way based on brilliant `wec_contentelements <http://typo3.org/extensions/repository/view/wec_contentelements>`_ extension.

You now have new typoscript objects to declare and make loop on section ::

    10 = FLEXFORM_SECTION

and new get datas objects **section** and **section_item** ::


    rootPath = section:images/el              # to define the loop root path
    ...
    data = section_item:image/el/caption      # accessing to a field in the loop

And that's it! To complete our previous example about slideshow, it could look like this ::

    tt_content.gridelements_pi1.20.10.setup.slideshow {
      prepend = COA
      prepend {
        5 = < lib.stdheader

        10 = FLEXFORM_SECTION
        10 {
          rootPath = section:images/el

          10 = COA
          10 {
            wrap = <div class="image">|</div>

            10 = IMAGE
            10{
              file.import.data = section_item:image/el/file
              file.import.wrap = uploads/skinFlex/slideshow/
            }

            20 = TEXT
            20{
              data = section_item:image/el/caption
              required = 1
              wrap = <p class="caption">|</p>
            }
          }
        }
      }

      outerWrap = <div class="slideshow slick-slider">|</div>
    }


Yeoman Generator
----------------
`Yeoman <http://yeoman.io/>`_ is a powerfull way to kickstart website, framework, ... In general, it's used for Javascript based code, but you can easily use it for you own purpose. That's why we've made a `wec_contentelements generator <https://github.com/Inouit/generator-wecce>`_  in the past and now we work on the *gridelements* transposal. You can check progress on the `github project <https://github.com/Inouit/generator-grid-fce>`_ and may be make some feedback.