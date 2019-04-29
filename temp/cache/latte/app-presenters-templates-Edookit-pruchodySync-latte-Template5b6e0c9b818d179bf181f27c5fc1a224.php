<?php
// source: /var/www/access/app/presenters/templates/Edookit/pruchodySync.latte

class Template5b6e0c9b818d179bf181f27c5fc1a224 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('80348f9e16', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
?>
<h1>Edookit synchronizace průchodů</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:"), ENT_COMPAT) ?>
">← Zpět na seznam skupin</a></p>

Počet odeslaných záznamu: <?php echo Latte\Runtime\Filters::escapeHtml($entries, ENT_NOQUOTES) ?> <br>
Počet chybně odeslaných záznamů: <?php echo Latte\Runtime\Filters::escapeHtml($err, ENT_NOQUOTES) ?>

<?php echo Latte\Runtime\Filters::escapeHtml($xml, ENT_NOQUOTES) ?>

<br>
url: <?php echo Latte\Runtime\Filters::escapeHtml($url, ENT_NOQUOTES) ?><br>
<?php echo Latte\Runtime\Filters::escapeHtml($context, ENT_NOQUOTES) ?>

<?php
}}