<?php

//  Include this in your WordPress theme (functions.php) or
//  plugin (plugin-name.php) to enable mega menu attributes
//  on the "Menus" admin page.

require_once(ABSPATH . 'wp-admin/includes/class-walker-nav-menu-edit.php');

class Walker_Nav_Menu_Edit_Fgms_Mega_Menu extends \Walker_Nav_Menu_Edit
{
    private function get($node)
    {
        while ($node && ($node->nodeType === 3)) $node = $node->nextSibling;
        return $node;
    }

    private function getFirst($node)
    {
        return $this->get($node->firstChild);
    }

    private function getNext($node)
    {
        return $this->get($node->nextSibling);
    }

    private function add(\DOMDocument $dom, $id, $node)
    {
        //  <li id="menu-item- ...
        $curr = $dom->getElementById('menu-item-' . $id);
        //  <div class="menu-item-bar">
        $curr = $this->getFirst($curr);
        //  <div class="menu-item-settings ...
        $curr = $this->getNext($curr);
        //  Find <p class="field-move ...
        for ($curr = $this->getFirst($curr); ; $curr = $this->getNext($curr)) {
            if (preg_match('/(?:^|\\s)field\\-move(?:$|\\s)/u',$curr->getAttribute('class'))) break;
        }
        //  Found our insertion point
        $curr->parentNode->insertBefore($node,$curr);
    }

    private function create(\DOMDocument $dom, $object)
    {
        $e = $dom->createElement('p');
        $e->setAttribute('class','field-mega-menu-template description description-thin');
        $id = 'edit-menu-item-mega-menu-template-' . $object->ID;
        $label = $dom->createElement('label');
        $label->setAttribute('for',$id);
        $label->appendChild($dom->createTextNode('Mega Menu Twig Template (optional)'));
        $label->appendChild($dom->createElement('br'));
        $input = $dom->createElement('input');
        $input->setAttribute('id',$id);
        $input->setAttribute('type','text');
        $input->setAttribute('class','widefat edit-menu-item-mega-menu-template');
        $input->setAttribute('name','menu-item-mega-menu-template[' . $object->ID . ']');
        $input->setAttribute('value',get_post_meta($object->ID,'mega_menu_template',true));
        $label->appendChild($input);
        $e->appendChild($label);
        return $e;
    }

    public function end_el(&$output, $object, $depth = 0, $args = array())
    {
        parent::end_el($output,$object,$depth,$args);
        $dom = new \DOMDocument();
        //  In some cases this complains about an unexpected ending li tag,
        //  but it seems to preserve it when it dumps the HTML, so it doesn't
        //  matter?
        //
        //  The @ operator is just there to suppress those warnings since
        //  they're just noise, we correctly check the return value for actual
        //  errors, so we're golden
        if (!@$dom->loadHTML($output,LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) throw new \RuntimeException(
            'Failed loading HTML fragment'
        );
        $e = $this->create($dom,$object);
        $this->add($dom,$object->ID,$e);
        $output = $dom->saveHTML();
        //  In case this object gets reused
        $this->objs = array();
    }
}

add_filter('wp_edit_nav_menu_walker',function ($walker, $menu_id) { return 'Walker_Nav_Menu_Edit_Fgms_Mega_Menu';   },10,2);
add_action('wp_update_nav_menu_item',function ($menu_id, $menu_item_db_id, $args) {
    if (!is_array($_REQUEST['menu-item-mega-menu-template'])) return;
    update_post_meta($menu_item_db_id,'mega_menu_template',$_REQUEST['menu-item-mega-menu-template'][$menu_item_db_id]);
},10,3);
