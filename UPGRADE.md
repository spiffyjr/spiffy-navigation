# Upgrading SpiffyNavigation

## Upgrading from 0.3
  * Page `properties` were removed in favor of `option` which is more appropriate to their function.
  * Deprecated the concept of page names. You should remove page names to the page 'options' and use findByOption
    or findOneByOption to keep old functionality. This will be removed in the 0.5.
  * ContainerFactory was deprecated and will be removed in 0.5. Use an ArrayProvider to keep the same functionality.

## Upgrading from 0.2
- Configuration key renamed from `spiffynavigation` to `spiffy_navigation` to follow the conventions of all my modules.
- `properties` in pages renamed to `options` to properly denote usage. Using `options` is deprecated and will trigger a
  warning but will still function.
