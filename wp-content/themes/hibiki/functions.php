<?php
/**
 * Required by WordPress.
 *
 * Keep this file clean and only use it for requires.
 */

// --------------------------------------------------------------------------
//   Include Utilities
// --------------------------------------------------------------------------

foreach (glob(dirname(__FILE__) . "/lib/utilities/*.php") as $filename) {
  require_once $filename;
}

// --------------------------------------------------------------------------
//   Include Configuration
// --------------------------------------------------------------------------

foreach (glob(dirname(__FILE__) . "/lib/config/*.php") as $filename) {
  require_once $filename;
}

// --------------------------------------------------------------------------
//   Include Post Types and Taxonomies
// --------------------------------------------------------------------------

foreach (glob(dirname(__FILE__) . "/lib/content/*.php") as $filename) {
  require_once $filename;
}

// --------------------------------------------------------------------------
//   Include Tweaks
// --------------------------------------------------------------------------

foreach (glob(dirname(__FILE__) . "/lib/tweaks/*.php") as $filename) {
  require_once $filename;
}
