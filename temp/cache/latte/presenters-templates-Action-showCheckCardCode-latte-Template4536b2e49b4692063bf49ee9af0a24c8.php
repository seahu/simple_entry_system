<?php
// source: /var/www/access/app/presenters/templates/Action/showCheckCardCode.latte

class Template4536b2e49b4692063bf49ee9af0a24c8 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('ccc5e2b17f', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
echo Latte\Runtime\Filters::escapeHtml($result, ENT_NOQUOTES) ;echo Latte\Runtime\Filters::escapeHtml($error, ENT_NOQUOTES) ;
}}