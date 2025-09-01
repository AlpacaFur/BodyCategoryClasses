<?php
namespace BodyCategoryClasses;
use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Parser\Sanitizer;

class Hooks implements BeforePageDisplayHook {
  public function onBeforePageDisplay($out, $skin): void {
    $config = $out->getConfig();
    $allowlist = $config->get("BodyCategoryClassesCategoryAllowList");
    $useAllowlist = $config->get("BodyCategoryClassesCategoryUseAllowList");

    // Get all non-hidden categories
    $categories = $out->getCategories('normal');

    $allowed_categories = $categories;

    // If the allowlist is enabled, only allow those categories.
    if($useAllowlist) {
      $allowed_categories = array_filter($categories, fn($category): bool => in_array($category, $allowlist));
    }

    // Escape as HTML class + add 'mw-x-category-' prefix
    $escaped_categories = array_map(fn($category): string => 'mw-x-category-' . Sanitizer::escapeClass($category), $allowed_categories);

    // Add classes to output
    $out->addBodyClasses($escaped_categories);
  }
}
