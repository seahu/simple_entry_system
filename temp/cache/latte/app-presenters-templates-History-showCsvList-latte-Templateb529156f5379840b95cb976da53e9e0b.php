<?php
// source: /var/www/access/app/presenters/templates/History/showCsvList.latte

class Templateb529156f5379840b95cb976da53e9e0b extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('01b3ce55fd', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
$iterations = 0; foreach ($cards as $card) { echo Latte\Runtime\Filters::escapeHtml($card->id, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($card->card_code, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($card->name, ENT_NOQUOTES) ?>,<?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($card->status, "1", "povoleno"), "0", "zakazano"), ENT_NOQUOTES) ?>

<?php $iterations++; } 
}}