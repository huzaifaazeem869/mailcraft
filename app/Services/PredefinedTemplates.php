<?php

namespace App\Services;

class PredefinedTemplates
{
    public static function all(): array
    {
        return [
            self::welcome(),
            self::passwordReset(),
            self::orderConfirmation(),
            self::invoice(),
            self::newsletter(),
            self::accountVerification(),
            self::abandonedCart(),
            self::supportTicket(),
            self::subscriptionRenewal(),
            self::securityAlert(),
        ];
    }

    public static function welcome(): array
    {
        return [
            'name' => 'Welcome Email',
            'slug' => 'welcome-email',
            'category' => 'Onboarding',
            'description' => 'A clean and engaging welcome email for newly signed up users.',
            'thumbnail' => '/templates/welcome.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '600px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f3f4f6',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1f2937',
                    'primaryColor' => '#4f46e5',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'b-logo',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://dummyimage.com/160x40/4f46e5/ffffff&text=YOUR+LOGO',
                            'alt' => 'Company Logo',
                            'align' => 'center',
                            'width' => '160px',
                            'maxWidth' => '160px',
                            'padding' => '32px 24px 16px 24px',
                        ]
                    ],
                    [
                        'id' => 'b-heading',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Welcome to {{company}}, {{name}}! 👋',
                            'level' => 'h1',
                            'align' => 'center',
                            'color' => '#111827',
                            'fontWeight' => 'bold',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'b-hero-img',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&auto=format&fit=crop&q=80',
                            'alt' => 'Welcome Illustration',
                            'align' => 'center',
                            'width' => '100%',
                            'maxWidth' => '552px',
                            'borderRadius' => '8px',
                            'padding' => '16px 24px',
                        ]
                    ],
                    [
                        'id' => 'b-text-1',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p>We are thrilled to have you on board! You\'ve just taken the first step towards supercharging your email workflows. Everything you need to get started is ready in your account.</p>',
                            'align' => 'left',
                            'fontSize' => '16px',
                            'lineHeight' => '1.6',
                            'color' => '#4b5563',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'b-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Go to Your Dashboard →',
                            'url' => 'https://example.com/dashboard',
                            'align' => 'center',
                            'bgColor' => '#4f46e5',
                            'textColor' => '#ffffff',
                            'borderRadius' => '6px',
                            'padding' => '20px 24px',
                            'paddingY' => '14px',
                            'paddingX' => '32px',
                        ]
                    ],
                    [
                        'id' => 'b-div',
                        'type' => 'divider',
                        'props' => [
                            'color' => '#e5e7eb',
                            'height' => '1px',
                            'padding' => '24px 24px',
                        ]
                    ],
                    [
                        'id' => 'b-social',
                        'type' => 'social',
                        'props' => [
                            'align' => 'center',
                            'iconSize' => '22px',
                            'spacing' => '8px',
                            'padding' => '8px 24px',
                            'items' => [
                                ['platform' => 'Twitter', 'url' => 'https://x.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733579.png'],
                                ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733561.png'],
                                ['platform' => 'GitHub', 'url' => 'https://github.com', 'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733553.png'],
                            ]
                        ]
                    ],
                    [
                        'id' => 'b-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}}',
                            'address' => 'Floor 4, Tech Plaza, Innovation Way',
                            'unsubscribeText' => 'Manage email preferences',
                            'unsubscribeUrl' => '{{unsubscribe_url}}',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function passwordReset(): array
    {
        return [
            'name' => 'Password Reset / OTP',
            'slug' => 'password-reset-otp',
            'category' => 'Authentication',
            'description' => 'Security token or password reset request with direct action button.',
            'thumbnail' => '/templates/password.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '540px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f8fafc',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1e293b',
                    'primaryColor' => '#0284c7',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'pr-logo',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://dummyimage.com/140x36/0284c7/ffffff&text=SECURE+APP',
                            'alt' => 'App Logo',
                            'align' => 'center',
                            'width' => '140px',
                            'maxWidth' => '140px',
                            'padding' => '36px 24px 16px 24px',
                        ]
                    ],
                    [
                        'id' => 'pr-heading',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Reset Your Password',
                            'level' => 'h2',
                            'align' => 'center',
                            'color' => '#0f172a',
                            'fontWeight' => 'bold',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'pr-text-1',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p>Hello <strong>{{name}}</strong>,</p><p>We received a request to reset your password for your <strong>{{email}}</strong> account. Click the button below to choose a new password:</p>',
                            'align' => 'left',
                            'fontSize' => '15px',
                            'lineHeight' => '1.6',
                            'color' => '#334155',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'pr-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Reset Password',
                            'url' => 'https://example.com/reset-password?token=sample123',
                            'align' => 'center',
                            'bgColor' => '#0284c7',
                            'textColor' => '#ffffff',
                            'borderRadius' => '6px',
                            'padding' => '16px 24px',
                        ]
                    ],
                    [
                        'id' => 'pr-text-otp',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; margin: 0; color: #64748b; font-size: 13px;">Or use this one-time security code:</p><p style="text-align: center; font-size: 26px; font-weight: bold; letter-spacing: 5px; color: #0f172a; margin: 8px 0 0 0;">849-201</p>',
                            'align' => 'center',
                            'padding' => '16px 24px',
                            'bgColor' => '#f1f5f9',
                        ]
                    ],
                    [
                        'id' => 'pr-note',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="font-size: 13px; color: #94a3b8; margin: 0;">This link and code will expire in 15 minutes. If you did not make this request, you can safely ignore this email.</p>',
                            'align' => 'left',
                            'padding' => '16px 24px',
                        ]
                    ],
                    [
                        'id' => 'pr-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Security Team',
                            'address' => 'Support Desk: security@example.com',
                            'unsubscribeText' => 'Security Notifications Policy',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function orderConfirmation(): array
    {
        return [
            'name' => 'Order Confirmation & Receipt',
            'slug' => 'order-confirmation-receipt',
            'category' => 'E-Commerce',
            'description' => 'Detailed e-commerce purchase receipt with items, order id and summary.',
            'thumbnail' => '/templates/order.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '600px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f9fafb',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#111827',
                    'primaryColor' => '#10b981',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'oc-logo',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://dummyimage.com/150x40/10b981/ffffff&text=STORE+LOGO',
                            'alt' => 'Store Logo',
                            'align' => 'center',
                            'width' => '150px',
                            'maxWidth' => '150px',
                            'padding' => '28px 24px 12px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-heading',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Order Confirmed! 🎉',
                            'level' => 'h1',
                            'align' => 'center',
                            'color' => '#065f46',
                            'fontWeight' => 'bold',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-text-1',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; margin: 0; color: #4b5563;">Thank you for your order, <strong>{{name}}</strong>! We are currently preparing your items for shipment.</p>',
                            'align' => 'center',
                            'padding' => '8px 24px 20px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-summary',
                        'type' => 'text',
                        'props' => [
                            'content' => '<table width="100%" cellpadding="6" cellspacing="0" style="font-size: 14px; border: 1px solid #e5e7eb; border-radius: 6px; background-color: #fcfdfd;">
                                <tr style="border-bottom: 1px solid #e5e7eb; background: #f3f4f6;">
                                    <th align="left" style="padding: 10px;">Order Number</th>
                                    <th align="right" style="padding: 10px;">Date</th>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; font-weight: bold; color: #10b981;">#{{order_id}}</td>
                                    <td align="right" style="padding: 10px; color: #6b7280;">October 14, 2026</td>
                                </tr>
                            </table>',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-items',
                        'type' => 'text',
                        'props' => [
                            'content' => '<table width="100%" cellpadding="8" cellspacing="0" style="font-size: 14px; border-collapse: collapse; margin-top: 10px;">
                                <tr style="border-bottom: 2px solid #e5e7eb; color: #374151;">
                                    <th align="left">Item</th>
                                    <th align="center">Qty</th>
                                    <th align="right">Price</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td>Wireless Noise-Cancelling Headphones</td>
                                    <td align="center">1</td>
                                    <td align="right">$149.00</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td>Braided USB-C Fast Charging Cable (2m)</td>
                                    <td align="center">2</td>
                                    <td align="right">$29.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right" style="padding-top: 12px; font-weight: bold;">Subtotal:</td>
                                    <td align="right" style="padding-top: 12px; font-weight: bold;">$178.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right" style="color: #6b7280;">Shipping:</td>
                                    <td align="right" style="color: #6b7280;">FREE</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right" style="font-size: 16px; font-weight: bold; color: #065f46; padding-top: 6px;">Total Paid:</td>
                                    <td align="right" style="font-size: 16px; font-weight: bold; color: #065f46; padding-top: 6px;">$178.00</td>
                                </tr>
                            </table>',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Track Your Shipment',
                            'url' => 'https://example.com/orders/{{order_id}}',
                            'align' => 'center',
                            'bgColor' => '#10b981',
                            'textColor' => '#ffffff',
                            'borderRadius' => '6px',
                            'padding' => '24px 24px',
                        ]
                    ],
                    [
                        'id' => 'oc-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} E-Commerce',
                            'address' => 'Customer Care: support@example.com',
                            'unsubscribeText' => 'Order Notification Settings',
                            'padding' => '16px 24px 28px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function invoice(): array
    {
        return [
            'name' => 'Professional Invoice',
            'slug' => 'professional-invoice',
            'category' => 'Billing',
            'description' => 'Corporate invoice with breakdown, payment link, and company tax details.',
            'thumbnail' => '/templates/invoice.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '600px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f1f5f9',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#0f172a',
                    'primaryColor' => '#2563eb',
                    'buttonRadius' => '4px',
                ],
                'blocks' => [
                    [
                        'id' => 'inv-header',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'INVOICE',
                            'level' => 'h1',
                            'align' => 'left',
                            'color' => '#1e3a8a',
                            'fontWeight' => 'bold',
                            'padding' => '32px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'inv-meta',
                        'type' => 'text',
                        'props' => [
                            'content' => '<table width="100%" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #475569;">
                                <tr>
                                    <td valign="top" width="50%">
                                        <strong style="color: #0f172a;">Billed To:</strong><br />
                                        {{name}}<br />
                                        {{company}}<br />
                                        {{email}}
                                    </td>
                                    <td valign="top" width="50%" align="right">
                                        <strong style="color: #0f172a;">Invoice #:</strong> INV-{{order_id}}<br />
                                        <strong>Date:</strong> October 14, 2026<br />
                                        <strong>Due Date:</strong> Upon Receipt
                                    </td>
                                </tr>
                            </table>',
                            'padding' => '8px 24px 16px 24px',
                        ]
                    ],
                    [
                        'id' => 'inv-div',
                        'type' => 'divider',
                        'props' => [
                            'color' => '#cbd5e1',
                            'height' => '2px',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'inv-table',
                        'type' => 'text',
                        'props' => [
                            'content' => '<table width="100%" cellpadding="10" cellspacing="0" style="font-size: 14px; border-collapse: collapse;">
                                <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #334155;">
                                    <th align="left">Service Description</th>
                                    <th align="center">Hours</th>
                                    <th align="right">Rate</th>
                                    <th align="right">Amount</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td>Custom Web Design & Frontend Layout</td>
                                    <td align="center">40</td>
                                    <td align="right">$65.00</td>
                                    <td align="right">$2,600.00</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td>Backend API & Authentication Setup</td>
                                    <td align="center">25</td>
                                    <td align="right">$65.00</td>
                                    <td align="right">$1,625.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right" style="padding-top: 14px; font-weight: bold;">Total Amount Due:</td>
                                    <td align="right" style="padding-top: 14px; font-size: 18px; font-weight: bold; color: #2563eb;">$4,225.00</td>
                                </tr>
                            </table>',
                            'padding' => '8px 24px 20px 24px',
                        ]
                    ],
                    [
                        'id' => 'inv-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Pay Invoice Online ($4,225.00)',
                            'url' => 'https://example.com/pay/INV-{{order_id}}',
                            'align' => 'center',
                            'bgColor' => '#2563eb',
                            'textColor' => '#ffffff',
                            'borderRadius' => '6px',
                            'padding' => '16px 24px',
                        ]
                    ],
                    [
                        'id' => 'inv-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Accounts Department',
                            'address' => 'Tax ID: US-94829482 • Billing support: billing@example.com',
                            'unsubscribeText' => 'Billing Preferences',
                            'padding' => '20px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function newsletter(): array
    {
        return [
            'name' => 'Newsletter / Promotional',
            'slug' => 'newsletter-promotional',
            'category' => 'Marketing',
            'description' => 'Rich editorial layout with featured article, 2-column highlights and promo banner.',
            'thumbnail' => '/templates/newsletter.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '600px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f3f4f6',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1f2937',
                    'primaryColor' => '#7c3aed',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'nl-header',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'THE WEEKLY DISPATCH 🗞️',
                            'level' => 'h2',
                            'align' => 'center',
                            'color' => '#7c3aed',
                            'fontWeight' => 'bold',
                            'padding' => '32px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-sub',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; color: #6b7280; font-size: 13px; margin: 0;">Issue #42 • Curated engineering, marketing, and design insights</p>',
                            'align' => 'center',
                            'padding' => '0px 24px 16px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-hero',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1200&auto=format&fit=crop&q=80',
                            'alt' => 'Tech article',
                            'align' => 'center',
                            'width' => '100%',
                            'maxWidth' => '552px',
                            'borderRadius' => '8px',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-h1',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'How Modern Agencies Build In Public',
                            'level' => 'h2',
                            'align' => 'left',
                            'color' => '#111827',
                            'padding' => '12px 24px 4px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-text',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p>Discover the exact playbooks agencies are using in 2026 to turn client case studies into automated pipeline generators. From micro-SaaS prototypes to reusable design systems...</p>',
                            'align' => 'left',
                            'fontSize' => '15px',
                            'lineHeight' => '1.6',
                            'color' => '#374151',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Read Full Story (5 min read) →',
                            'url' => 'https://example.com/blog/build-in-public',
                            'align' => 'left',
                            'bgColor' => '#7c3aed',
                            'textColor' => '#ffffff',
                            'padding' => '8px 24px 24px 24px',
                        ]
                    ],
                    [
                        'id' => 'nl-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Weekly',
                            'address' => 'Sent to {{email}} because you subscribed to our newsletter.',
                            'unsubscribeText' => 'Unsubscribe instantly',
                            'unsubscribeUrl' => '{{unsubscribe_url}}',
                            'padding' => '24px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function accountVerification(): array
    {
        return [
            'name' => 'Account Verification',
            'slug' => 'account-verification',
            'category' => 'Authentication',
            'description' => 'Verify user email address upon registration to confirm identity.',
            'thumbnail' => '/templates/verify.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '560px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f8fafc',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#0f172a',
                    'primaryColor' => '#3b82f6',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'av-icon',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://cdn-icons-png.flaticon.com/512/190/190411.png',
                            'alt' => 'Check mark',
                            'align' => 'center',
                            'width' => '64px',
                            'maxWidth' => '64px',
                            'padding' => '36px 24px 12px 24px',
                        ]
                    ],
                    [
                        'id' => 'av-head',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Verify Your Email Address',
                            'level' => 'h2',
                            'align' => 'center',
                            'color' => '#1e293b',
                            'padding' => '8px 24px',
                        ]
                    ],
                    [
                        'id' => 'av-text',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; color: #475569; font-size: 15px; margin: 0;">Hi <strong>{{name}}</strong>, thanks for creating an account with <strong>{{company}}</strong>. Please confirm your email address by clicking the button below:</p>',
                            'align' => 'center',
                            'padding' => '12px 24px 20px 24px',
                        ]
                    ],
                    [
                        'id' => 'av-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Confirm My Account',
                            'url' => 'https://example.com/verify?email={{email}}',
                            'align' => 'center',
                            'bgColor' => '#3b82f6',
                            'textColor' => '#ffffff',
                            'padding' => '12px 24px 24px 24px',
                        ]
                    ],
                    [
                        'id' => 'av-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}}',
                            'address' => 'If you did not sign up for this account, please ignore this email.',
                            'unsubscribeText' => 'Privacy Policy',
                            'padding' => '20px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function abandonedCart(): array
    {
        return [
            'name' => 'Abandoned Cart Recovery',
            'slug' => 'abandoned-cart-recovery',
            'category' => 'E-Commerce',
            'description' => 'Remind shoppers about items left behind in their basket with an incentive discount.',
            'thumbnail' => '/templates/cart.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '580px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#fef2f2',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1f2937',
                    'primaryColor' => '#ef4444',
                    'buttonRadius' => '8px',
                ],
                'blocks' => [
                    [
                        'id' => 'ac-head',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Did you leave something behind? 🛒',
                            'level' => 'h2',
                            'align' => 'center',
                            'color' => '#b91c1c',
                            'fontWeight' => 'bold',
                            'padding' => '36px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'ac-text',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; color: #4b5563; font-size: 15px; margin: 0;">Hey {{name}}, good news! We saved the items in your cart so you can easily complete your checkout whenever you\'re ready.</p>',
                            'align' => 'center',
                            'padding' => '8px 24px 20px 24px',
                        ]
                    ],
                    [
                        'id' => 'ac-img',
                        'type' => 'image',
                        'props' => [
                            'src' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                            'alt' => 'Saved Product',
                            'align' => 'center',
                            'width' => '220px',
                            'maxWidth' => '220px',
                            'borderRadius' => '8px',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'ac-offer',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; font-size: 16px; font-weight: bold; color: #111827; margin: 0;">Premium Wireless ANC Headphones</p><p style="text-align: center; font-size: 14px; color: #dc2626; margin: 6px 0 0 0;">Use promo code <strong>SAVE10</strong> for 10% off your order!</p>',
                            'align' => 'center',
                            'padding' => '8px 24px 16px 24px',
                        ]
                    ],
                    [
                        'id' => 'ac-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Complete My Order →',
                            'url' => 'https://example.com/checkout?discount=SAVE10',
                            'align' => 'center',
                            'bgColor' => '#ef4444',
                            'textColor' => '#ffffff',
                            'padding' => '16px 24px 28px 24px',
                        ]
                    ],
                    [
                        'id' => 'ac-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}}',
                            'address' => 'Offers valid for 48 hours.',
                            'unsubscribeText' => 'Unsubscribe from cart reminders',
                            'unsubscribeUrl' => '{{unsubscribe_url}}',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function supportTicket(): array
    {
        return [
            'name' => 'Support Ticket Reply',
            'slug' => 'support-ticket-reply',
            'category' => 'Customer Care',
            'description' => 'Clean helpdesk agent response with ticket status and feedback link.',
            'thumbnail' => '/templates/support.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '580px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f8fafc',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1e293b',
                    'primaryColor' => '#0d9488',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'st-head',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Support Request Update #{{order_id}}',
                            'level' => 'h2',
                            'align' => 'left',
                            'color' => '#0f766e',
                            'padding' => '32px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'st-body',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p>Hi <strong>{{name}}</strong>,</p><p>A customer support agent has updated your ticket with the following response:</p><blockquote style="border-left: 3px solid #0d9488; margin: 16px 0; padding-left: 12px; color: #475569; font-style: italic;">"We have reviewed your inquiry and applied the necessary configuration updates to your workspace. Everything should now be working as expected."</blockquote><p>You can view the full conversation history or reply directly by clicking the link below:</p>',
                            'align' => 'left',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'st-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'View Ticket #{{order_id}}',
                            'url' => 'https://example.com/tickets/{{order_id}}',
                            'align' => 'left',
                            'bgColor' => '#0d9488',
                            'textColor' => '#ffffff',
                            'padding' => '12px 24px 24px 24px',
                        ]
                    ],
                    [
                        'id' => 'st-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Helpdesk',
                            'address' => 'Support Hours: Monday - Friday 9am - 6pm EST',
                            'unsubscribeText' => 'Ticket Notifications',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function subscriptionRenewal(): array
    {
        return [
            'name' => 'Subscription Renewal Notice',
            'slug' => 'subscription-renewal-notice',
            'category' => 'Billing',
            'description' => 'Upcoming plan renewal or expiry notice with plan details and payment update button.',
            'thumbnail' => '/templates/renewal.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '560px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f5f3ff',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#1e1b4b',
                    'primaryColor' => '#6d28d9',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'sr-head',
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Your Plan Renews Soon',
                            'level' => 'h2',
                            'align' => 'center',
                            'color' => '#5b21b6',
                            'padding' => '32px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'sr-text',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p style="text-align: center; color: #4338ca; font-size: 15px; margin: 0;">Hi {{name}}, this is a friendly reminder that your <strong>Pro Agency Plan</strong> will renew automatically on <strong>November 1, 2026</strong> for <strong>$49/month</strong>.</p>',
                            'align' => 'center',
                            'padding' => '12px 24px 20px 24px',
                        ]
                    ],
                    [
                        'id' => 'sr-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Manage Subscription & Billing',
                            'url' => 'https://example.com/billing',
                            'align' => 'center',
                            'bgColor' => '#6d28d9',
                            'textColor' => '#ffffff',
                            'padding' => '12px 24px 28px 24px',
                        ]
                    ],
                    [
                        'id' => 'sr-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Subscriptions',
                            'address' => 'Questions? Reach out to support@example.com',
                            'unsubscribeText' => 'Billing emails cannot be unsubscribed',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function securityAlert(): array
    {
        return [
            'name' => 'Simple Notification / Alert',
            'slug' => 'security-system-alert',
            'category' => 'Security',
            'description' => 'Important security warning or system notification requiring user awareness.',
            'thumbnail' => '/templates/alert.png',
            'is_predefined' => true,
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '540px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#fffbeb',
                    'fontFamily' => 'Arial, Helvetica, sans-serif',
                    'textColor' => '#451a03',
                    'primaryColor' => '#d97706',
                    'buttonRadius' => '6px',
                ],
                'blocks' => [
                    [
                        'id' => 'sa-head',
                        'type' => 'heading',
                        'props' => [
                            'text' => '⚠️ Security Notice: New Sign-in',
                            'level' => 'h2',
                            'align' => 'left',
                            'color' => '#92400e',
                            'padding' => '32px 24px 8px 24px',
                        ]
                    ],
                    [
                        'id' => 'sa-body',
                        'type' => 'text',
                        'props' => [
                            'content' => '<p>Hello <strong>{{name}}</strong>,</p><p>We detected a new sign-in to your <strong>{{email}}</strong> account from an unrecognized device:</p><div style="background: #fef3c7; padding: 12px 16px; border-radius: 6px; font-size: 14px; margin: 12px 0;"><strong>Device:</strong> Chrome on Windows 11<br /><strong>Location:</strong> Karachi, Pakistan<br /><strong>IP Address:</strong> 182.185.120.44</div><p>If this was you, you can safely disregard this alert. If you do not recognize this activity, please secure your account immediately.</p>',
                            'align' => 'left',
                            'padding' => '12px 24px',
                        ]
                    ],
                    [
                        'id' => 'sa-btn',
                        'type' => 'button',
                        'props' => [
                            'text' => 'Review Account Activity',
                            'url' => 'https://example.com/security/sessions',
                            'align' => 'left',
                            'bgColor' => '#d97706',
                            'textColor' => '#ffffff',
                            'padding' => '12px 24px 24px 24px',
                        ]
                    ],
                    [
                        'id' => 'sa-footer',
                        'type' => 'footer',
                        'props' => [
                            'company' => '{{company}} Security Operations',
                            'address' => 'Automated security alert message',
                            'unsubscribeText' => 'Security settings',
                            'padding' => '16px 24px 32px 24px',
                        ]
                    ]
                ]
            ]
        ];
    }
}
