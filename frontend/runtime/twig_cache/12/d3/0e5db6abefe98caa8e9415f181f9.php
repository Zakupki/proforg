<?php

/* views/layouts/main.twig */
class __TwigTemplate_12d30e5db6abefe98caa8e9415f181f9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'headScript' => array($this, 'block_headScript'),
            'wrapper' => array($this, 'block_wrapper'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        ob_start();
        // line 2
        echo "    ";
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerCssFile", array(0 => ("/css/style.css?v=" . twig_date_format_filter($this->env, "now", "U"))), "method"));
        echo "
    ";
        // line 3
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerScriptFile", array(0 => "/js/head.js"), "method"));
        echo "
    ";
        // line 4
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerScriptFile", array(0 => "/js/jquery-1.7.2.min.js"), "method"));
        echo "
    ";
        // line 5
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerScriptFile", array(0 => "/js/jquery.masonry.min.js"), "method"));
        echo "
    ";
        // line 6
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerScriptFile", array(0 => "/js/jquery.infinitescroll.min.js"), "method"));
        echo "
    ";
        // line 7
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute($_App_, "getClientScript"), "registerScriptFile", array(0 => ("/js/scripts.js?v=" . twig_date_format_filter($this->env, "now", "U"))), "method"));
        echo "
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 9
        echo "<!DOCTYPE html>
<html dir=\"ltr\" lang=\"";
        // line 10
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_App_, "language"), "html", null, true);
        echo "\" class=\"no-js\">
<head>
    <meta charset=\"utf-8\"/>
    <title>";
        // line 13
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
    <base href=\"";
        // line 14
        if (isset($context["App"])) { $_App_ = $context["App"]; } else { $_App_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_App_, "params"), "siteUrl"), "html", null, true);
        echo "/\">
    <!--[if lte IE 8]>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/ie.css\" media=\"all\"/>
    <script type=\"text/javascript\" src=\"/js/css3-mediaqueries_src.js\"></script>
    <![endif]-->

    <link rel=\"shortcut icon\" href=\"favicon.ico\"/>
    <style type=\"text/css\">
        ";
        // line 22
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($_this_, "data"), "sites", array(), "array"));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 23
            echo "            .bgc-";
            if (isset($context["item"])) { $_item_ = $context["item"]; } else { $_item_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_item_, "code", array(), "array"), "html", null, true);
            echo "  { background-color: #";
            if (isset($context["item"])) { $_item_ = $context["item"]; } else { $_item_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_item_, "color", array(), "array"), "html", null, true);
            echo "; }
            .clr-";
            // line 24
            if (isset($context["item"])) { $_item_ = $context["item"]; } else { $_item_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_item_, "code", array(), "array"), "html", null, true);
            echo "  { color: #";
            if (isset($context["item"])) { $_item_ = $context["item"]; } else { $_item_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_item_, "color", array(), "array"), "html", null, true);
            echo "; }
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 26
        echo "    </style>

    ";
        // line 28
        $this->displayBlock('headScript', $context, $blocks);
        // line 29
        echo "</head>

<body class=\"";
        // line 31
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($this->getAttribute($_this_, "data"), "bodyClass", array(), "array"), " "), "html", null, true);
        echo "\">
    <div class=\"wrapper ";
        // line 32
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_this_, "data"), "pageCode", array(), "array"), "html", null, true);
        echo "\">
        ";
        // line 33
        $this->displayBlock('wrapper', $context, $blocks);
        // line 34
        echo "    </div>
</body>
";
        // line 36
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo $this->getAttribute($this->getAttribute($_this_, "data"), "ga", array(), "array");
        echo "
</html>";
    }

    // line 13
    public function block_title($context, array $blocks = array())
    {
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_this_, "pageTitle"), "html", null, true);
    }

    // line 28
    public function block_headScript($context, array $blocks = array())
    {
        echo "<script type=\"text/javascript\">var shareUrl = \"";
        if (isset($context["this"])) { $_this_ = $context["this"]; } else { $_this_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_this_, "createUrl", array(0 => "site/share"), "method"), "html", null, true);
        echo "\";</script>";
    }

    // line 33
    public function block_wrapper($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "views/layouts/main.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  157 => 33,  148 => 28,  141 => 13,  134 => 36,  130 => 34,  128 => 33,  123 => 32,  118 => 31,  114 => 29,  112 => 28,  108 => 26,  96 => 24,  87 => 23,  82 => 22,  70 => 14,  66 => 13,  59 => 10,  50 => 7,  35 => 4,  30 => 3,  24 => 2,  22 => 1,  71 => 21,  62 => 16,  56 => 9,  52 => 12,  45 => 6,  40 => 5,  37 => 6,  34 => 5,  29 => 3,);
    }
}
