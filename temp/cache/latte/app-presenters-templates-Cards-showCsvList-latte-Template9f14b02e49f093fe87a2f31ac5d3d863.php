<?php
// source: /var/www/access/app/presenters/templates/Cards/showCsvList.latte

class Template9f14b02e49f093fe87a2f31ac5d3d863 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('ad5510f3d7', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
$iterations = 0; foreach ($cards as $card) { ?>
	<?php echo Latte\Runtime\Filters::escapeHtml($card->card_code, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($card->name, ENT_NOQUOTES) ?>,<?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($card->access, "1", "povoleno"), "0", "zakazano"), ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($card->status, "1", "přítomen"), "0", "nepřítomen"), ENT_NOQUOTES) ?> <br>
<?php $iterations++; } 
}}