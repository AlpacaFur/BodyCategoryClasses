<?php
use MediaWiki\Output\Hook\BeforePageDisplayHook;

class AddBodyCategoryClassesHook implements BeforePageDisplayHook {
  public function onBeforePageDisplay($out, $skin): void {
    global $wgBodyCategoryClassesCategoryAllowList;
    global $wgBodyCategoryClassesCategoryUseAllowList;

    // Get all non-hidden categories
    $categories = $out->getCategories('normal');

    $allowed_categories = $categories;

    // If the allowlist is enabled, only allow those categories.
    if($wgBodyCategoryClassesCategoryUseAllowList) {
      $allowed_categories = array_filter($categories, fn($category): bool => in_array($category, $wgBodyCategoryClassesCategoryAllowList));
    }

    // Escape as HTML class + add 'mw-x-category-' prefix
    $escaped_categories = array_map(fn($category): string => 'mw-x-category-' . Sanitizer::escapeClass($category), $allowed_categories);



    // Add classes to output
    $out->addBodyClasses($escaped_categories);
  }
}
