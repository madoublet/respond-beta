<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // get settings
  $primary_color = env('PRIMARY_COLOR');
  $primary_dark_color = env('PRIMARY_DARK_COLOR');

  $drawer_color = env('DRAWER_COLOR', $primary_color);

  // create css
  $css = <<<EOD
a, a:visited {
  color: $primary_color;
}

body .app-selector ul {
  background-color: $primary_color;
}

body .app-list-item .primary a {
  color: $primary_color;
}


.app-drawer a, .app-overflow a {
  color: $primary_color;
}

body .app-modal a, body .app-modal a:visited {
  color: $primary_color
}

body .app-modal .actions button {
  color: $primary_color;
}

body .app-modal .app-modal-list-item small {
  color: $primary_color;
}

/* app-menu */
.app-menu {
  background-color: $primary_color;
}

/* save */
.app-menu .app-save, .app-menu .app-add, .app-menu .app-overflow {
  background-color: $primary_color;
}

/* menu */
.app-menu .app-more {
  background-color: $primary_color;
}

.app-menu .app-more:hover {
  background-color: $primary_color;
}

.app-menu a:hover {
  background-color: $primary_color;
}

.app-slideshow-container div.description a.primary {
  border: 1px solid $primary_color;
  color: $primary_color;
}

/* app large screen */
@media (min-width:768px) {

.app-drawer li, .app-drawer li.app-drawer-title, .app-drawer a {
  color: #fff;
}

.app-drawer {
  background-color: $drawer_color;
}

.app-menu .app-add, .app-menu .app-overflow {
  color: $primary_color !important;
}

body .app-selector li a {
  color: $primary_color;
}

body .app-selector li.selected a {
  border-bottom: 3px solid $primary_color;
}

.app-menu {
  background-color: #fff;
}

body .app-selector ul {
  background-color: #fff;
}

.app-menu .app-add, .app-menu .app-overflow {
  background-color: #fff;
}

}

/* hashedit */
[hashedit-element]:hover {
  box-shadow: inset 0 0 0 1px $primary_color;
}

[hashedit-block]:hover {
  box-shadow: inset 0 0 0 1px $primary_color;
}

[hashedit-block] .hashedit-block-menu {
  background-color: $primary_color;
}

.hashedit-element-menu {
  background-color: $primary_color;
}

.hashedit-move:hover span,.hashedit-properties:hover span,.hashedit-remove:hover span {
  color: $primary_color;
}

body .hashedit-config a:hover,body .hashedit-config a:hover {
  background-color: $primary_color;
}

.hashedit-menu a:hover {
  background-color: $primary_color !important;
}

body .hashedit-modal a,body .hashedit-modal a:visited {
  color: $primary_color;
}

body .hashedit-config {
  background-color: $primary_dark_color;
}

.hashedit-menu {
  color: $primary_dark_color;
}

.hashedit-menu .hashedit-save,.hashedit-menu .hashedit-add {
  color: $primary_dark_color !important;
}

.hashedit-menu .hashedit-back {
  color: $primary_dark_color;
}

.hashedit-menu .hashedit-back:hover {
  background-color: $primary_dark_color;
}

.hashedit-menu a {
  color: $primary_dark_color;
}

EOD;

  send_response(200, $css, $type = 'text/css');

  ?>