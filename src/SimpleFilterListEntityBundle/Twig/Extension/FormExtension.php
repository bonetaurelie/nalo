<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/08/16
 * Time: 16:18
 */

namespace SimpleFilterListEntityBundle\Twig\Extension;


use Symfony\Bridge\Twig\Form\TwigRendererInterface;
use Symfony\Component\Form\FormView;

/**
 * Twig extension for render the Javascript block of the form type
 *
 * Class FormExtension
 *
 * @author Nicolas PIN <pin.nicolas@free.fr>
 *
 * @package SimpleFilterListEntityBundle\Twig\Extension
 */
class FormExtension extends \Twig_Extension
{
    /**
     * This property is public so that it can be accessed directly from compiled
     * templates without having to call a getter, which slightly decreases performance.
     *
     * @var \Symfony\Component\Form\FormRendererInterface
     */
    public $renderer;

    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_javascript', array($this, 'renderJavascript'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Render Function Form Javascript
     *
     * @param FormView $view
     * @param bool $prototype
     *
     * @return string
     */
    public function renderJavascript(FormView $view, $prototype = false)
    {
        return $this->renderer->searchAndRenderBlock($view, 'javascript');
    }

    public function getName()
    {
       return 'filter_list.extension.form';
    }
}