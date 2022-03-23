# WP Partytown
This plugin is a POC for ticket [#176](https://github.com/WordPress/performance/issues/176) in [WordPress/performance](https://github.com/WordPress/performance) repo.

# Goals
1. Allow plugin developers to execute scripts inside a Partytown Web Worker.
2. Allow site admins to select which scripts they want to execute inside a Partytown Web Worker.

# Proposal and Working of Plugin
## 1. Scripts with dependency as `partytown` will be executed inside a Web Worker.
Create a function to enqueue scripts with dependency as `partytown` and it will add partytown support to the script by appending the `type="text/partytown"` attribute.

```php
wp_enqueue_script(
  'non-critical-script',
  plugins_url( 'non-critical-script.js', __FILE__ ),
  array('partytown'),
  '1.0.0'
);
```
Output:
```html
// Loaded in Partytown Web Worker
<script type="text/partytown" src="/wp-content/plugins/labs/non-critical-script.js?ver=1.0.0"></script>
```

## 2. Expose a filter to allow plugins to configure partytown
By default Partytown does not require a config for it to work, however, a config can be set to change the defaults. At the lowest level, it’s configured by setting the `window.partytown = {...}` object before the Partytown snippet script.

```php
function my_plugin_partytown_config( $config ) {
  $config["debug"] = true;
  return $config;
}
add_filter( 'partytown_configuration', 'my_plugin_partytown_config' );
```
```html
<script>
    window.partytown = {
        debug: true
    };
</script>
```

## 3. Show option to enable/disable partytown on Website
To get started with Partytown on a website, you need to add a script `partytown.js` to your website. While the partytown.js file could be an external request, it’s recommended to inline the script instead.

Showing an option to enable it ensures that script should be included only when needed.
