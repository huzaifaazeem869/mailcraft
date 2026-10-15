<?php

namespace App\Services;

class EmailHtmlRenderer
{
    /**
     * Convert design JSON into email-client safe HTML
     *
     * @param array $design
     * @return string
     */
    public function render(array $design): string
    {
        $brand = $design['brand'] ?? [];
        $blocks = $design['blocks'] ?? [];

        $canvasWidth = $brand['canvasWidth'] ?? '600px';
        $canvasBg = $brand['canvasBg'] ?? '#ffffff';
        $bodyBg = $brand['bodyBg'] ?? '#f4f6f8';
        $fontFamily = $brand['fontFamily'] ?? 'Arial, Helvetica, sans-serif';
        $textColor = $brand['textColor'] ?? '#333333';
        $primaryColor = $brand['primaryColor'] ?? '#4f46e5';

        $renderedBlocks = '';
        foreach ($blocks as $block) {
            $renderedBlocks .= $this->renderBlock($block, $brand);
        }

        return <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td, p, a, li, blockquote {
            font-family: Arial, Helvetica, sans-serif !important;
        }
    </style>
    <![endif]-->
    <style type="text/css">
        /* Client-specific Resets */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: {$bodyBg}; }

        /* Responsive Styles */
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .stack-column-center {
                text-align: center !important;
            }
            .mobile-padding {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            .img-fluid {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: {$bodyBg}; font-family: {$fontFamily}; color: {$textColor}; -webkit-font-smoothing: antialiased;">
    <center style="width: 100%; background-color: {$bodyBg};">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellspacing="0" cellpadding="0" width="{$canvasWidth}">
        <tr>
        <td align="center" valign="top" width="{$canvasWidth}">
        <![endif]-->
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: {$canvasWidth}; margin: 0 auto; background-color: {$canvasBg};">
            <tr>
                <td style="padding: 0;">
                    {$renderedBlocks}
                </td>
            </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </center>
</body>
</html>
HTML;
    }

    /**
     * Render a single block
     */
    public function renderBlock(array $block, array $brand): string
    {
        $type = $block['type'] ?? 'text';
        $props = $block['props'] ?? [];
        $bg = $props['bgColor'] ?? 'transparent';
        $padding = $props['padding'] ?? '16px 24px';
        $align = $props['align'] ?? 'left';

        switch ($type) {
            case 'heading':
                $text = htmlspecialchars($props['text'] ?? 'Heading');
                $level = $props['level'] ?? 'h1';
                $fontSize = match ($level) {
                    'h1' => '28px',
                    'h2' => '22px',
                    'h3' => '18px',
                    default => '24px'
                };
                $color = $props['color'] ?? ($brand['textColor'] ?? '#111827');
                $weight = $props['fontWeight'] ?? 'bold';
                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="{$align}" style="padding: {$padding};">
            <h1 style="margin: 0; font-size: {$fontSize}; font-weight: {$weight}; color: {$color}; line-height: 1.3; text-align: {$align};">
                {$text}
            </h1>
        </td>
    </tr>
</table>
HTML;

            case 'text':
                $content = $props['content'] ?? '<p>Write your content here...</p>';
                $color = $props['color'] ?? ($brand['textColor'] ?? '#374151');
                $fontSize = $props['fontSize'] ?? '16px';
                $lineHeight = $props['lineHeight'] ?? '1.6';
                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="{$align}" style="padding: {$padding}; font-size: {$fontSize}; line-height: {$lineHeight}; color: {$color}; text-align: {$align};">
            {$content}
        </td>
    </tr>
</table>
HTML;

            case 'button':
                $btnText = htmlspecialchars($props['text'] ?? 'Click Here');
                $url = htmlspecialchars($props['url'] ?? '#');
                $btnBg = $props['bgColor'] ?? ($brand['primaryColor'] ?? '#4f46e5');
                $btnColor = $props['textColor'] ?? '#ffffff';
                $borderRadius = $props['borderRadius'] ?? ($brand['buttonRadius'] ?? '6px');
                $paddingY = $props['paddingY'] ?? '12px';
                $paddingX = $props['paddingX'] ?? '24px';
                $fontSize = $props['fontSize'] ?? '15px';
                $fontWeight = $props['fontWeight'] ?? 'bold';
                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="{$align}" style="padding: {$padding};">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" style="border-radius: {$borderRadius}; background-color: {$btnBg};">
                        <a href="{$url}" target="_blank" style="font-size: {$fontSize}; font-weight: {$fontWeight}; color: {$btnColor}; text-decoration: none; border-radius: {$borderRadius}; padding: {$paddingY} {$paddingX}; display: inline-block; border: 1px solid {$btnBg};">
                            {$btnText}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

            case 'image':
                $src = htmlspecialchars($props['src'] ?? 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=800&auto=format&fit=crop');
                $alt = htmlspecialchars($props['alt'] ?? 'Image');
                $width = $props['width'] ?? '100%';
                $maxWidth = $props['maxWidth'] ?? '600px';
                $url = $props['url'] ?? null;
                $borderRadius = $props['borderRadius'] ?? '0px';

                $imgTag = "<img src=\"{$src}\" alt=\"{$alt}\" border=\"0\" referrerpolicy=\"no-referrer\" style=\"display: block; width: {$width}; max-width: {$maxWidth}; height: auto; border-radius: {$borderRadius};\" class=\"img-fluid\" />";
                if ($url) {
                    $imgTag = "<a href=\"" . htmlspecialchars($url) . "\" target=\"_blank\">{$imgTag}</a>";
                }

                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="{$align}" style="padding: {$padding};">
            {$imgTag}
        </td>
    </tr>
</table>
HTML;

            case 'divider':
                $borderColor = $props['color'] ?? '#e5e7eb';
                $borderWidth = $props['height'] ?? '1px';
                $borderStyle = $props['style'] ?? 'solid';
                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td style="padding: {$padding};">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="border-top: {$borderWidth} {$borderStyle} {$borderColor}; font-size: 1px; line-height: 1px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

            case 'spacer':
                $height = $props['height'] ?? '24px';
                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td height="{$height}" style="font-size: 1px; line-height: {$height}; mso-line-height-rule: exactly;">&nbsp;</td>
    </tr>
</table>
HTML;

            case 'columns':
                $colsCount = (int)($props['count'] ?? 2);
                $columns = $props['columns'] ?? [];
                $widthPercent = floor(100 / $colsCount);

                $columnsHtml = '';
                for ($i = 0; $i < $colsCount; $i++) {
                    $colBlocks = $columns[$i] ?? [];
                    $renderedInner = '';
                    foreach ($colBlocks as $innerBlock) {
                        $renderedInner .= $this->renderBlock($innerBlock, $brand);
                    }
                    $columnsHtml .= <<<HTML
<!--[if (gte mso 9)|(IE)]>
<td align="left" valign="top" width="{$widthPercent}%">
<![endif]-->
<table border="0" cellpadding="0" cellspacing="0" width="{$widthPercent}%" align="left" class="stack-column" style="vertical-align: top;">
    <tr>
        <td style="padding: 10px;">
            {$renderedInner}
        </td>
    </tr>
</table>
<!--[if (gte mso 9)|(IE)]>
</td>
<![endif]-->
HTML;
                }

                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td style="padding: {$padding};">
            <!--[if (gte mso 9)|(IE)]>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
            <![endif]-->
            {$columnsHtml}
            <!--[if (gte mso 9)|(IE)]>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>
HTML;

            case 'social':
                $items = $props['items'] ?? [
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733547.png'],
                    ['platform' => 'Twitter', 'url' => 'https://x.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733579.png'],
                    ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733561.png'],
                    ['platform' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733558.png'],
                ];
                $iconSize = $props['iconSize'] ?? '24px';
                $spacing = $props['spacing'] ?? '12px';

                $linksHtml = '';
                foreach ($items as $item) {
                    $itemUrl = htmlspecialchars($item['url'] ?? '#');
                    $iconSrc = htmlspecialchars($item['icon'] ?? '');
                    $plat = htmlspecialchars($item['platform'] ?? '');
                    $linksHtml .= <<<HTML
<td style="padding: 0 {$spacing};">
    <a href="{$itemUrl}" target="_blank" style="text-decoration: none;">
        <img src="{$iconSrc}" alt="{$plat}" width="{$iconSize}" height="{$iconSize}" style="display: block; border: 0;" />
    </a>
</td>
HTML;
                }

                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="{$align}" style="padding: {$padding};">
            <table border="0" cellspacing="0" cellpadding="0" align="{$align}">
                <tr>
                    {$linksHtml}
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

            case 'footer':
                $company = htmlspecialchars($props['company'] ?? '{{company}}');
                $address = htmlspecialchars($props['address'] ?? '123 Business Street, Suite 100, City, Country');
                $unsubscribeText = htmlspecialchars($props['unsubscribeText'] ?? 'Unsubscribe');
                $unsubscribeUrl = htmlspecialchars($props['unsubscribeUrl'] ?? '{{unsubscribe_url}}');
                $color = $props['color'] ?? '#9ca3af';
                $fontSize = $props['fontSize'] ?? '12px';

                return <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {$bg};">
    <tr>
        <td align="center" style="padding: {$padding}; font-size: {$fontSize}; line-height: 1.5; color: {$color}; text-align: center;">
            <p style="margin: 0 0 8px 0;">&copy; {{current_year}} {$company}. All rights reserved.</p>
            <p style="margin: 0 0 8px 0;">{$address}</p>
            <p style="margin: 0;">
                <a href="{$unsubscribeUrl}" target="_blank" style="color: {$color}; text-decoration: underline;">
                    {$unsubscribeText}
                </a>
            </p>
        </td>
    </tr>
</table>
HTML;

            default:
                return '';
        }
    }
}
