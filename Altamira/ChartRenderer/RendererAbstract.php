<?php 

namespace Malwarebytes\AltamiraBundle\Altamira\ChartRenderer;

abstract class RendererAbstract
{
    abstract public static function preRender( \Malwarebytes\AltamiraBundle\Altamira\Chart $chart, array $styleOptions = array() );
    
    abstract public static function postRender( \Malwarebytes\AltamiraBundle\Altamira\Chart $chart, array $styleOptions = array() );
    
    public static function renderStyle( array $styleOptions = array() ) 
    {
        return '';
    }
    
    public static function renderData( \Malwarebytes\AltamiraBundle\Altamira\Chart $chart ) 
    {
        return '';
    }
}
