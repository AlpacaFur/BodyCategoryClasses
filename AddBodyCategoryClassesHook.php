<?php
class AddBodyCategoryClassesHook {
  public function __construct(private readonly Config $config,) {}

  public function onBeforePageDisplay($out, $skin): void {
    $allowlist = $this->config->get("BodyCategoryClassesCategoryAllowList");
    $useAllowlist = $this->config->get("BodyCategoryClassesCategoryUseAllowList");

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
