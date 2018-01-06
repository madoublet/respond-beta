<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // get settings
  $primary_color = env('PRIMARY_COLOR');
  $primary_dark_color = env('PRIMARY_DARK_COLOR');

  // create css
  $css = <<<EOD

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

body .hashedit-config, body .hashedit-help {
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