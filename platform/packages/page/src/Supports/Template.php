<?php

namespace Botble\Page\Supports;

class Template
{
    /**
     * @param $templates
     * @return void
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    public static function registerPageTemplate($templates = [])
    {
        $validTemplates = [];
        foreach ($templates as $key => $template) {
            if (in_array($key, self::getExistsTemplate())) {
                $validTemplates[$key] = $template;
            }
        }

        config([
            'packages.page.general.templates' => array_merge(config('packages.page.general.templates'), $validTemplates),
        ]);
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    protected static function getExistsTemplate()
    {
        $files = scan_folder(theme_path(setting('theme') . DIRECTORY_SEPARATOR . config('packages.theme.general.containerDir.layout')));
        foreach ($files as $key => $file) {
            $files[$key] = str_replace('.blade.php', '', $file);
        }

        return $files;
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    public static function getPageTemplates()
    {
        return config('packages.page.general.templates', []);
    }
}
