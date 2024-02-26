<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/** [TAG] => POST key map for METADATA */
const PAGE_BUILDER_TAGS = [
    '[PAGE_BUILDER_TITLE]' => 'title',
    '[PAGE_BUILDER_SEO_DESC]' => 'desc',
    '[PAGE_BUILDER_SEO_AUTHOR]' => 'author',
    '[PAGE_BUILDER_SEO_KEYWORDS]' => 'keywords',
    '[PAGE_BUILDER_SEO_IMAGE]' => 'og_image',
    '[PAGE_BUILDER_SEO_ROBOT]' => 'robots',
    '[PAGE_BUILDER_CANONICAL]' => 'canonical',
    '[PAGE_BUILDER_CUSTOM_CSS]' => 'css',
];

/**
 * List of whitelisted host for iframes, img and medias
 *
 * @return array
 */
function page_builder_whitelisted_hosts()
{
    $allowedHosts = [
        'youtube.com',
        'player.vimeo.com',
        'twitter.com',
        'openstreetmap.org',
        'google.com',
        'googleapis.com',
        'paypalobjects.com',
        'paypal.com',
        'unsplash.com',
        'placeholder.com',
        'wrappixel.com'
    ];

    $allowedHosts[] = parse_url(base_url(), PHP_URL_HOST);

    $customWhitelist = explode(',', (page_builder_get_options()['whitelist'] ?? ''));
    $allowedHosts = array_merge($allowedHosts, $customWhitelist);

    return $allowedHosts;
}

/**
 * Act on a page metadata.
 * The function will copy, renaname, update or delete the metadata for the page.
 * @param string $file . Should exist except for delete and rename action.
 * @param array $metadata
 * @param string $newfile . should exist for renaming or duplicating action
 * @return array Builder options
 */
function page_builder_metadata(string $file, array $metadata = [], $newfile = '')
{

    $options = page_builder_get_options();

    $fileHash = hash("md5", $file);
    $newfileHash = empty($newfile) ? '' : hash("md5", $newfile);

    $fileExist  = file_exists($file);
    $newfileExist = !empty($newfile) && file_exists($newfile);

    // Delete
    if (!$fileExist && empty($newfile) && isset($options[$fileHash])) {
        unset($options[$fileHash]);
    }

    // Copying or updating details
    if ($fileExist && $newfileExist && isset($options[$fileHash])) {
        $options[$newfileHash] = $options[$fileHash];
    }

    // Renaming
    if (!$fileExist && $newfileExist) {

        if (isset($options[$fileHash])) {
            $options[$newfileHash] = $options[$fileHash];
            unset($options[$fileHash]);
        }

        $fileHash = $newfileHash;
    }

    // Updating
    if ($fileExist && empty($newfile) && !empty($metadata)) {
        // Extract the needed data and clean
        $cleanedMetadata = [];
        foreach (PAGE_BUILDER_TAGS as $key => $value) {
            if (isset($metadata[$value])) {
                $cleanedMetadata[$key] = xss_clean(page_builder_remove_css_comments($metadata[$value]));
            }
        }

        // Merge with old
        $options[$fileHash] = array_merge($options[$fileHash] ?? [], $cleanedMetadata);
    }

    // Save
    page_builder_save_options($options);

    return $options;
}

/**
 * Get the options for the page builder.
 *
 * @return array
 */
function page_builder_get_options()
{
    return json_decode(get_option('page_builder_options') ?? '', true) ?? [];
}

/**
 * Save option
 *
 * @param array $options
 * @return bool
 */
function page_builder_save_options($options)
{
    $oldoptions = (array)page_builder_get_options();
    $options = array_merge($oldoptions, $options);
    return update_option('page_builder_options', json_encode($options), false);
}


/**
 * Get metadata options for a page file.
 *
 * @param string $file
 * @return array
 */
function page_builder_get_metadata(string $file)
{
    $options = page_builder_get_options();
    return $options[hash("md5", $file)] ?? [];
}


/**
 * Remove css comment from string
 *
 * @param string $cssString
 * @return void
 */
function page_builder_remove_css_comments(string $cssString)
{
    // Regular expression to match CSS comments (/* ... */)
    $pattern = '/\/\*.*?\*\//s';

    // Remove CSS comments using preg_replace
    $cleanedString = preg_replace($pattern, '', $cssString);

    return $cleanedString;
}

/**
 * Remove directory recursively including hidder directories and files.
 * This is preferable to perfex delete_dir function as that does not handle hidden directories well.
 *
 * @param      string  $target  The directory to remove
 * @return     bool
 */
function page_builder_remove_dir($target)
{
    try {
        if (is_dir($target)) {
            $dir = new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $filename => $file) {
                if (is_file($filename)) {
                    unlink($filename);
                } else {
                    page_builder_remove_dir($filename);
                }
            }
            return rmdir($target); // Now remove target folder
        }
    } catch (\Exception $e) {
    }
    return false;
}


/**
 * Get the path where pages are stored.
 * This is not absolute but relative to the media folder.
 *
 * @param string $extra_path
 * @return string
 */
function page_builder_pages_path($extra_path = '')
{
    $extra_path = empty($extra_path) || str_starts_with($extra_path, '/') ? $extra_path : '/' . $extra_path;
    return  get_instance()->app->get_media_folder() . '/public/' . PAGE_BUILDER_MODULE_NAME . $extra_path;
}

/**
 * Get the path and url of the theme.
 * Path first the http url.
 *
 * @return array Path first, theme fancy url and real http url without trailing slash
 */
function page_builder_get_theme_path_url()
{
    $path = page_builder_pages_path('/pages');
    $themePath = FCPATH . $path;
    $themeRealUrl = base_url($path);
    $themeUrl = trim(base_url(), '/');
    return [$themePath,  $themeUrl, $themeRealUrl];
}

/**
 * Get all html files inside a dir
 *
 * @param string $dir The folder to fetch html files from
 * @return array
 */
function page_builder_get_dir_html_files($dir)
{
    $htmlFiles = [];

    // Ensure the directory exists
    if (!is_dir($dir)) return $htmlFiles;

    $patterns = [$dir . '/*/*.html', $dir . '/*.html'];
    // Get all files matching the patterns
    foreach ($patterns as $pattern) {
        $htmlFiles = array_merge($htmlFiles, glob($pattern));
    }
    return $htmlFiles;
}

/**
 * Get all html pages in the pages folder.
 * The use can select which page to use as the landing page.
 *
 * @return array
 */
function page_builder_get_pages()
{
    $pages = [];
    list($themePath, $themeUrl) = page_builder_get_theme_path_url();

    $htmlFiles = page_builder_get_dir_html_files($themePath);

    $activeTheme = page_builder_get_options()['landingpage'] ?? '';
    $activeThemeIndex = 0;
    foreach ($htmlFiles as $index => $file) {

        if (stripos($file, 'new-page-blank-template.html') !== false) continue; //skip template files
        $pathInfo = pathinfo($file);
        $extension = $pathInfo['extension'];
        if ($extension !== 'html') continue;

        $basePath = str_ireplace($themePath, '', $pathInfo['dirname']);
        $realFilename = $filename = $pathInfo['filename'];
        $paths = explode('/', trim($basePath, '/'));
        $folder = $paths[0];
        unset($paths[0]);
        $subfolder = join('/', $paths);

        if ($subfolder) {
            if ($folder !== $subfolder)
                $filename = $subfolder . '/' . $filename;
        }


        $url = str_ireplace($themePath, $themeUrl, $pathInfo['dirname'] . '/' . $pathInfo['basename']);

        $page = [
            "name" =>  $file,
            "file" => str_ireplace($themePath, '', $file),
            "title" => ucfirst($filename),
            "url" => $url,
            "folder" => empty($folder) ? basename($subfolder) : $folder,
            "base_path_url" => str_ireplace(basename($realFilename) . '.' . $extension, '', $url)
        ];
        $pages[$index] = $page;

        if ($activeTheme == $page['file'])
            $activeThemeIndex = $index;
    }

    if ($activeThemeIndex) {
        // sort make acitve theme first one 
        $activeTheme = $pages[$activeThemeIndex];
        unset($pages[$activeThemeIndex]);
        $pages = array_merge([$activeTheme], $pages);
    }

    return $pages;
}

/**
 * Get parsed content of a page from path.
 * It add csrf to local form, parse assets files url to absolute.
 *
 * @param string $pagePath
 * @param string $pageUrl
 * @param boolean $redirect
 * @return string
 */
function page_builder_get_page_content($pagePath, $pageUrl, $redirect = false)
{
    if ($redirect) {
        if (is_client_logged_in()) {
            return redirect('clients');
        }

        if (is_staff_logged_in()) {
            return redirect('admin');
        }
    }

    $page_body_content = file_get_contents($pagePath);

    $page_body_content = str_ireplace(
        ['"assets/', '\'assets/', '(assets/'],
        ['"' . $pageUrl . '/assets/', "'$pageUrl/assets/", "(" . $pageUrl . '/assets/'],
        $page_body_content
    );

    return $page_body_content;
}


/**
 * Validate and serve page file name conotent.
 *
 * @param string $page The page file name path without full path i.e /landing.html relative to the pages base url.
 * @return void
 */
function page_builder_serve_page($page)
{
    list($pagePath, $pageUrl, $pageRealUrl) = page_builder_get_theme_path_url();

    // Serve if the file exist as html
    if (str_ends_with($page, '.html')) {
        $pagePath = str_replace('//', '/', $pagePath . '/' . $page);
        if (file_exists($pagePath)) {

            // Module asset dir url
            $assetPath =  module_dir_url(PAGE_BUILDER_MODULE_NAME, 'assets');

            // Get the page content
            $pageContent = page_builder_get_page_content($pagePath, $pageRealUrl);
            $pageContent = str_ireplace('[PAGE_BUILDER_ASSET_BASE_URL]', $assetPath, $pageContent);
            $pageContent = page_builder_html_purify($pageContent);

            // Parse content with the default template
            $layout = file_get_contents(module_dir_path(PAGE_BUILDER_MODULE_NAME, 'views/pages_layout_template.html'));

            // Locale
            $language = get_option('active_language');
            $locale = get_locale_key($language);

            // Add saved page metadaa and others to the header
            $metadata = page_builder_get_metadata($pagePath);
            $pageData = [
                '[PAGE_BUILDER_CONTENT]' => $pageContent,
                '[PAGE_BUILDER_PAGE_URL]' => $pageUrl,
                '[PAGE_BUILDER_ASSET_BASE_URL]' => $assetPath,
                '[PAGE_BUILDER_FAVICON]' => get_option('favicon'),
                '[PAGE_BUILDER_LANG]' => $locale,
            ];

            $defaultValues = [
                '[PAGE_BUILDER_SEO_ROBOT]' => 'index, follow',
                '[PAGE_BUILDER_TITLE]' => get_option('company_name'),
            ];

            foreach (PAGE_BUILDER_TAGS as $tag => $value) {
                $pageData[$tag] = xss_clean(!empty($metadata[$tag]) ? $metadata[$tag] : ($defaultValues[$tag] ?? ''));
            }

            $pageData['[PAGE_BUILDER_CUSTOM_CSS]'] = strip_tags(html_entity_decode(page_builder_html_purify($pageData['[PAGE_BUILDER_CUSTOM_CSS]'], 'style')));

            echo str_ireplace(
                array_keys($pageData),
                array_values($pageData),
                $layout
            );
            exit;
        }
    }
}

/**
 * Purify HTML content.
 * The sanitizer will adapt its rules to only allow elements that are valid inside the given parent element.
 * 
 * @param string $content
 * @param string $element The parent element.
 * @return string The cleaned safe HTML
 */
function page_builder_html_purify(string $content, $element = 'body')
{

    $config = new HtmlSanitizerConfig();

    if ($element === 'body') {

        $allowedHosts = page_builder_whitelisted_hosts();

        $config = $config->allowSafeElements()
            ->allowLinkHosts($allowedHosts)
            ->allowMediaHosts($allowedHosts)
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowMediaSchemes(['http', 'https', 'mailto'])
            ->allowRelativeLinks()
            ->allowElement('iframe', ['src', 'height', 'width', 'allowfullscreen'])
            ->allowElement('svg', ['height', 'width', 'viewbox', 'xmlns', 'preserveaspectratio', 'enable-background', 'x', 'y'])
            ->allowElement('path', ['d', 'transform', 'opacity', 'fill'])
            ->allowElement('g', ['id'])
            ->allowElement('form', ['action', 'method'])
            ->allowElement('input', ['name', 'placeholder', 'type', 'src', 'value'])
            ->allowElement('select', ['name'])
            ->allowElement('option', ['value', 'selected'])
            ->allowElement('textarea', ['name', 'placeholder', 'value'])
            ->allowElement('canvas', ['width', 'height'])
            ->allowElement('ul')
            ->allowElement('li')
            ->allowElement('sub')
            ->withMaxInputLength(1024 * 1024 * 2);

        $globalAttributes = ['id', 'class', 'style'];

        // Common data attribute for bootstrap and custom components
        $dataAttributes = [
            'data-name', 'data-toggle', 'data-target', 'data-dismiss', 'data-offset', 'data-animation', 'data-delay', 'data-html', 'data-placement', 'data-title', 'data-trigger', 'data-viewport', 'data-loading-text',
            'data-bs-toggle', 'data-bs-target', 'data-bs-ride', 'data-bs-dismiss', 'data-bs-offset', 'data-bs-animation', 'data-bs-container', 'data-bs-delay', 'data-bs-placement', 'data-bs-selector', 'data-bs-template', 'data-bs-title', 'data-bs-trigger', 'data-bs-viewport', 'data-bs-loading-text', 'data-bs-complete-text', 'data-bs-slide-to', 'data-bs-slide', 'data-bs-interval', 'data-bs-pause', 'data-bs-wrap', 'data-bs-keyboard', 'data-bs-offset-top', 'data-bs-offset-bottom',
            'data-aos', 'data-aos-anchor', 'data-aos-anchor-placement', 'data-aos-delay', 'data-aos-duration', 'data-aos-easing', 'data-aos-mirror', 'data-aos-once', 'data-aos-offset', 'data-aos-placement', 'data-aos-anchor-id',
            'aria-checked', 'aria-disabled', 'aria-hidden', 'aria-invalid', 'aria-label', 'aria-labelledby', 'aria-required', 'aria-describedby', 'aria-expanded', 'aria-haspopup', 'aria-selected',
            'data-chart', 'data-value', 'data-controls', 'tabindex'
        ];

        foreach (array_merge($globalAttributes, $dataAttributes) as $attr) {
            $config = $config->allowAttribute($attr, '*');
        }
    }

    $htmlSanitizer = new HtmlSanitizer(
        $config
    );

    $safeContents = $htmlSanitizer->sanitizeFor($element, $content);

    return trim($safeContents);
}
