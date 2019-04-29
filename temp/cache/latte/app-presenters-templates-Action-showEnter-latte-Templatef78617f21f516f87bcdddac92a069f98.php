<?php
// source: /var/www/access/app/presenters/templates/Action/showEnter.latte

class Templatef78617f21f516f87bcdddac92a069f98 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('986cd008c5', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb2d496f1698_content')) { function _lb2d496f1698_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a></p>
<h1>Akce</h1>
<?php $_l->tmp = $_control->getComponent("enterForm"); if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); $_l->tmp->render() ?>
<hr>
PS: Nepoužívat toto slouží spíše jen jako ukázka jak se do systému dají zadat průchody.
Pokud vrátný potřebuje ručně zaevidovat průchod např. když student zapomene kartu <br>je pohodlnější použít položku vložit příchod/odchod v seznamu karet. <br>  
Průchody se standardně vkládají pomocí externího programu, který vyčítá události na čtečkách karet
a následně je vkládá na teto odkaz <br> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Action:showCheckCardCode", array('card_code','address')), ENT_COMPAT) ?>
">http://IP_this_server/access/action/show-check-card-code?card_code=15154654&card_reader_address=4564646 </a>

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