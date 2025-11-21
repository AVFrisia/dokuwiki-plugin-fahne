<?php

use dokuwiki\Extension\SyntaxPlugin;

/**
 * DokuWiki Plugin fahne (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author Johannes Arnold <johannes@rnold.online>
 */
class syntax_plugin_fahne extends SyntaxPlugin
{
    /** @inheritDoc */
    public function getType()
    {
        return 'substitution';
    }

    /** @inheritDoc */
    public function getPType()
    {
        return 'FIXME: normal';
    }

    /** @inheritDoc */
    public function getSort()
    {
        return 35;
    }

    /** @inheritDoc */
    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('<fahne .*?>', $mode, 'plugin_fahne');
//        $this->Lexer->addEntryPattern('<FIXME>', $mode, 'plugin_fahne');
    }

//    /** @inheritDoc */
//    public function postConnect()
//    {
//        $this->Lexer->addExitPattern('</FIXME>', 'plugin_fahne');
//    }

    /** @inheritDoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        // Remove <fahne and trailing >
        $inner = trim(substr($match, 6, -1));   // removes <fahne ...>

        // Remove optional leading/trailing whitespace
        $inner = trim($inner);

        // Parse comma-separated colors
        $colors = array_map('trim', explode(',', $inner));
        
        return $colors;
    }

    /** @inheritDoc */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode !== 'xhtml') {
            return false;
        }

        $count = count($colors);
        if($count < 2) {
            $renderer->doc .= "<p><strong>Error:</strong> <fahne> needs at least 2 colors.</p>";
            return true;
        }

        // SVG dimensions
        $width = 300;
        $height = 200;
        $barWidth = $width / $count;

        // Build SVG
        $svg  = "<svg width='{$width}' height='{$height}' xmlns='http://www.w3.org/2000/svg'>";

        for($i = 0; $i < $count; $i++) {
            $x = $i * $barWidth;
            $color = htmlspecialchars($colors[$i]);
            $svg .= "<rect x='{$x}' y='0' width='{$barWidth}' height='{$height}' fill='{$color}' />";
        }

        $svg .= "</svg>";

        // Output inline SVG
        $renderer->doc .= $svg;

        return true;
    }
}
