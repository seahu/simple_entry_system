<?php
// source: /var/www/access/app/presenters/templates/Cards/showNewCard.latte

class Template3d232dafa5651fdfcab9fc9d1e22851c extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('f2d35c1a74', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb824f6cb5a6_content')) { function _lb824f6cb5a6_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:showList"), ENT_COMPAT) ?>
">← Zpět na seznam karet</a></p>
<h1>Přidat novou kartu</h1>
<?php $_l->tmp = $_control->getComponent("newCardForm"); if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); $_l->tmp->render() ?>

<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start(function () {});}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars()) ; 
}}