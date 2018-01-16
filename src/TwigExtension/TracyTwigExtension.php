<?php

namespace Drupal\tracy\TwigExtension;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Theme\ThemeManagerInterface;

use Tracy\Debugger;
use Tracy\Dumper;

/**
 * @package Tracy
 * @author Michal Landsman <landsman@studioart.cz>
 */
class TracyTwigExtension extends \Drupal\Core\Template\TwigExtension
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param RendererInterface $renderer
     * @param UrlGeneratorInterface $url_generator
     * @param ThemeManagerInterface $theme_manager
     * @param DateFormatterInterface $date_formatter
     */
    public function __construct
    (
        RendererInterface $renderer,
        UrlGeneratorInterface $url_generator,
        ThemeManagerInterface $theme_manager,
        DateFormatterInterface $date_formatter
    )
    {
        /**
         * @todo: think about config from admin UI
         */
        $this->options = [
            Dumper::COLLAPSE       => 0,
            Dumper::COLLAPSE_COUNT => 0,
            Dumper::DEPTH          => 99,
        ];


        parent::__construct($renderer, $url_generator, $theme_manager, $date_formatter, $date_formatter);
    }


    /**
     * Gets a unique identifier for this Twig extension.
     */
    public function getName()
    {
        return 'tracy.twig_extension';
    }


    /**
     * Generate a list of all twig functions
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dump',        [$this, 'dump'], ['is_safe' => ['html'], 'needs_context' => true, 'needs_environment' => true]),
            new \Twig_SimpleFunction('bdump',       [$this, 'barDump']),
            new \Twig_SimpleFunction('bDump',       [$this, 'barDump']),
            new \Twig_SimpleFunction('barDump',     [$this, 'barDump']),
            new \Twig_SimpleFunction('__barDump',   [$this, 'barDump']),
        ];
    }


    /**
     * @param \Twig_Environment $environment
     * @param $context
     * @return string
     */
    public function dump(\Twig_Environment $environment, $context)
    {
        if (!$environment->isDebug()) {
            return '';
        }
        $arguments = func_get_args();
        array_shift($arguments);
        array_shift($arguments);
        $count = count($arguments);
        if ($count === 0) {
            $arguments = $context;
        }
        if ($count === 1) {
            $arguments = array_shift($arguments);
        }
        return $this->doDump($arguments);
    }


    /**
     * @param $data
     * @return string
     */
    protected function doDump($data)
    {
        ob_start();
        Dumper::dump($data, $this->options);


        return ob_get_clean();
    }


    /**
     * @param $var
     * @param string $title
     */
    public function barDump($var, $title = 'Twig dump')
    {
        Debugger::barDump($var, $title, $this->options);
    }
}