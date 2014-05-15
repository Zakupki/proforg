<?php

/* //views/site/error.twig */
class __TwigTemplate_0665c25f6696cac18ee8fb5f513dd528 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("views/layouts/main.twig");

        $this->blocks = array(
            'headScript' => array($this, 'block_headScript'),
            'wrapper' => array($this, 'block_wrapper'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "views/layouts/main.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headScript($context, array $blocks = array())
    {
    }

    // line 5
    public function block_wrapper($context, array $blocks = array())
    {
        // line 6
        echo "    <header class=\"header\">
        <a href=\"";
        // line 7
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_this_, "createUrl", array(0 => "/"), "method"), "html", null, true);
        echo "\" class=\"logo\">
            <strong>";
        // line 8
        echo twig_escape_filter($this->env, Option::getOpt("site.title"), "html", null, true);
        echo "</strong>
            <img src=\"/img/logo.png\" alt=\"\"/>
        </a>

        <strong class=\"error-title\">";
        // line 12
        echo twig_escape_filter($this->env, Yii::t("frontend", "error 404"), "html", null, true);
        echo "</strong>
        <h1 class=\"header-title\">";
        // line 13
        echo twig_escape_filter($this->env, Yii::t("frontend", "page not found"), "html", null, true);
        echo "</h1>

        <nav class=\"navigation\">
            ";
        // line 16
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, ETwigViewRendererVoidFunction($this->getAttribute($_this_, "widget", array(0 => "zii.widgets.CMenu", 1 => array("items" => $this->getAttribute($this->getAttribute($_this_, "menu"), "main"))), "method")), "html", null, true);
        echo "
        </nav>
    </header>

    <footer class=\"footer\">
        <p class=\"copyright\">";
        // line 21
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_this_, "data"), "copyright", array(), "array"), "html", null, true);
        echo "</p>
    </footer>
";
    }

    public function getTemplateName()
    {
        return "//views/site/error.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 21,  62 => 16,  56 => 13,  52 => 12,  45 => 8,  40 => 7,  37 => 6,  34 => 5,  29 => 3,);
    }
}
