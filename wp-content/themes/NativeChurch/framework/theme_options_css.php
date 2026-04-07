<?php
$options_saved = get_option('imic_options');
$css = '';
if ($options_saved && isset($options_saved['content_padding_dimensions'])) {
    $saved_css = get_option('nativechurch_dynamic_css');
    if ($saved_css == '') {
        $fonts_args = array('family' => '', 'subset' => '');
        $font_family = $font_subset = array();
        foreach ($options_saved as $key => $value) {
            if ($key == 'content_padding_dimensions') {
                $class = '.content';
                $style = 'padding-top:' . $value['padding-top'] . ';';
                $style = 'padding-bottom:' . $value['padding-bottom'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'content_background') {
                $class = '.content';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'header_background_alpha' && isset($value['rgba'])) {
                $class = '.site-header .topbar';
                $style = 'background-color:' . $value['rgba'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'header_background_image') {
                $class = '.site-header .topbar';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'sticky_header_background_alpha' && isset($value['rgba'])) {
                $class = '.is-sticky .main-menu-wrapper, .header-style4 .is-sticky .site-header .topbar, .header-style2 .is-sticky .main-menu-wrapper';
                //$class = '.site-header .topbar';
                $style = 'background-color:' . $value['rgba'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'sticky_header_background') {
                $class = '.is-sticky .main-menu-wrapper, .header-style4 .is-sticky .site-header .topbar, .header-style2 .is-sticky .main-menu-wrapper';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'sticky_link_color') {
                $class = '.is-sticky .navigation > ul > li > a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'navigation_background_alpha') {
                $class = '.navigation, .header-style2 .main-menu-wrapper';
                $style = 'background-color:' . $value['color'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'main_nav_typo') {
                $class = '.navigation > ul > li > a';
                $style = '';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'mainmenu_link_color') {
                $class = '.navigation > ul > li > a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'main_dropdown_background_alpha') {
                $class = '.navigation > ul > li ul';
                $css .= $class . '{background-color:' . $value['color'] . ';}';
                $class = '.navigation > ul > li.megamenu > ul:before, .navigation > ul > li ul:before';
                $css .= $class . '{border-bottom-color:' . $value['color'] . ';}';
                $class = '.navigation > ul > li ul li ul:before';
                $css .= $class . '{border-right-color:' . $value['color'] . ';}';
            } elseif ($key == 'main_menu_dropdown_border') {
                $class = '.navigation > ul > li > ul li > a';
                $style = '';
                foreach ($value as $tag => $st) {
                    $style .= 'border-bottom:' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'main_nav_dropdown_typo') {
                $class = '.navigation > ul > li > ul li > a';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'main_dropdown_link_color') {
                $class = '.navigation > ul > li > ul li > a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'top_nav_typo') {
                $class = '.top-navigation > li > a';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'topmenu_link_color') {
                $class = '.top-navigation > li > a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'top_dropdown_background_alpha') {
                $class = '.top-navigation > li ul';
                $css .= $class . '{background-color:' . $value['color'] . ';}';
                $class = '.top-navigation > li.megamenu > ul:before, .top-navigation > li ul:before';
                $css .= $class . '{border-bottom-color:' . $value['color'] . ';}';
                $class = '.top-navigation > li ul li ul:before';
                $css .= $class . '{border-right-color:' . $value['color'] . ';}';
            } elseif ($key == 'top_menu_dropdown_border') {
                $class = '.top-navigation > li > ul li > a';
                $style = '';
                foreach ($value as $tag => $st) {
                    $style .= 'border-bottom:' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'top_nav_dropdown_typo') {
                $class = '.top-navigation > li > ul li > a';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'top_dropdown_link_color') {
                $class = '.top-navigation > li > ul li > a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'top_footer_background_alpha') {
                $class = '.site-footer';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'footer_padding') {
                $class = '.site-footer';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'top_footer_typo') {
                $class = '.site-footer, .site-footer p';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'top_footer_widgets_typo') {
                $class = '.site-footer .widgettitle';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'top_footer_widget_border' && isset($value['border-bottom'])) {
                $class = '.site-footer .listing-header, .site-footer .post-title, .site-footer .listing .item, .site-footer .post-meta, .site-footer .widget h4.footer-widget-title, .site-footer .widget ul > li';
                $style = 'border-bottom:' . $value['border-bottom'] . ' ' . $value['border-style'] . ' ' . $value['border-color'];
            } elseif ($key == 'top_footer_link_color') {
                $class = '.site-footer a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'bottom_footer_background_alpha') {
                $class = '.site-footer-bottom';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'copyrights_padding') {
                $class = '.site-footer-bottom';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'bottom_footer_typo') {
                $class = '.site-footer-bottom .copyrights-col-left';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'bottom_footer_link_color') {
                $class = '.site-footer-bottom .copyrights-col-left a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'footer_social_background_alpha') {
                $class = '.site-footer-bottom .social-icons a';
                $style = 'background-color:' . $value['color'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'footer_social_background_hover_alpha') {
                $class = '.site-footer-bottom .social-icons a:hover';
                $style = 'background-color:' . $value['color'] . ';';
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'footer_social_link_color') {
                $class = '.site-footer-bottom .social-icons a';
                $style = 'color:' . $value['regular'] . ';';
                $css .= $class . '{' . $style . '}';
                $css .= $class . ':hover{color:' . $value['hover'] . ';}';
                $css .= $class . ':active{color:' . $value['active'] . ';}';
            } elseif ($key == 'footer_social_link_dimensions') {
                $class = '.site-footer-bottom .social-icons a';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'footer_social_link_typo') {
                $class = '.site-footer-bottom .social-icons a';
                $style = 'line-height:' . $value['line-height'] . ';';
                $style = 'font-size:' . $value['font-size'] . ';';
            } elseif ($key == 'body_font_typography') {
                $class = 'h1,h2,h3,h4,h5,h6,body,.event-item .event-detail h4,.site-footer-bottom';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'heading_font_typography') {
                $class = 'h4,.title-note,.btn,.top-navigation,.navigation,.notice-bar-title strong,.timer-col #days, .timer-col #hours, .timer-col #minutes, .timer-col #seconds,.event-date,.event-date .date,.featured-sermon .date,.page-header h1,.timeline > li > .timeline-badge span,.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'metatext_date_font_typography') {
                $class = 'blockquote p,.cursive,.meta-data,.fact';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_font_typo') {
                $class = '.page-content, .page-content p';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h1_font_typo') {
                $class = '.page-content h1';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h2_font_typo') {
                $class = '.page-content h2';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h3_font_typo') {
                $class = '.page-content h3';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h4_font_typo') {
                $class = '.page-content h4';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h5_font_typo') {
                $class = '.page-content h5';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            } elseif ($key == 'body_h6_font_typo') {
                $class = '.page-content h6';
                $font_separator = '';
                $subset_separator = '';
                $font_family[] = (isset($value['google']) && $value['google'] == true && $value['font-family'] != '') ? $font_separator . $value['font-family'] : '';
                $font_subset[] = (isset($value['google']) && $value['google'] == true && $value['subsets'] != '') ? $subset_separator . $value['subsets'] : '';
                $style = '';
                foreach ($value as $tag => $st) {
                    if ($st == '' || is_array($st) || $tag == 'google' || $tag == 'units') continue;
                    if ($tag == 'background-image') {
                        $st = 'url("' . $st . '")';
                    }
                    $style .= $tag . ':' . $st . ';';
                }
                $css .= $class . '{' . $style . '}';
            }
        }
        $font_family_implode = implode('|', array_unique(array_filter($font_family)));
        $font_subset_implode = implode(',', array_unique(array_filter($font_subset)));
        update_option('nativechurch_dynamic_css', $css);
        update_option('nativechurch_dynamic_fonts', array('family' => $font_family_implode, 'subset' => $font_subset_implode));
    } else {
        function nativechurch_enqueue_dynamic_css()
        {
            $dynamic_css = get_option('nativechurch_dynamic_css');
            $dynamic_fonts = get_option('nativechurch_dynamic_fonts');
            if ($dynamic_css != '1' && $dynamic_css != '') {
                $theme_info = wp_get_theme();
                wp_enqueue_style('nativechurch-fonts', add_query_arg($dynamic_fonts, "//fonts.googleapis.com/css"), array(), $theme_info->get('Version'), 'all');
                wp_add_inline_style('imic_main', $dynamic_css);
            }
        }
        add_action('wp_enqueue_scripts', 'nativechurch_enqueue_dynamic_css', 9999);
    }
}
add_action('redux/options/imic_options/saved', 'nativechurch_use_new_dynamic_css');
function nativechurch_use_new_dynamic_css()
{
    update_option('nativechurch_dynamic_css', '1');
}
