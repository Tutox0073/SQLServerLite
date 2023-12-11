import {defineConfig} from 'vitepress'

export default defineConfig({
    title: "Laravel SQLServerLite",
    description: "An easy, fast, and efficient way to use SQL Server databases in your Laravel developments.",
    lang: 'en-US',
    lastUpdated: false,
    base: '/SQLServerLite',
    themeConfig: {
        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2021-2023 Raul Mauricio Uñate'
        },
        editLink: {
            pattern: 'https://github.com/rmunate/SQLServerLite/tree/main/docs/:path'
        },
        logo: '/img/logo.png',
        nav: [
            {text: 'v2.0.0', link: '/'},
        ],
        sidebar: [
            {
                text: 'Getting Started',
                collapsed: false,
                items: [
                    {text: 'Introduction', link: '/getting-started/introduction'},
                    {text: 'Installation', link: '/getting-started/installation'},
                    {text: 'Release Notes', link: '/getting-started/changelog'},
                ]
            }, {
                text: 'Usage',
                collapsed: false,
                items: [
                    {text: 'Numbers To Letters', link: '/usage/numbers-to-letters'},
                    {text: 'Numbers To Money', link: '/usage/numbers-to-money'},
                    {text: 'Numbers To Ordinal', link: '/usage/numbers-to-ordinal'},
                    {text: 'Config File', link: '/usage/config-file'},
                    {text: 'Config Custom Callback', link: '/usage/config-custom-callback'},
                ]
            }, {
                text: 'Contribute',
                collapsed: false,
                items: [
                    {text: 'Bug Report', link: 'contribute/report-bugs'},
                    {text: 'Contribution', link: 'contribute/contribution'}
                ]
            }
        ],

        socialLinks: [
            {icon: 'github', link: 'https://github.com/rmunate/SQLServerLite'}
        ],
        search: {
            provider: 'local'
        }
    },
    head: [
        ['link', { 
                rel: 'stylesheet', 
                href: '/SQLServerLite/css/style.css' 
            }
        ],
        ['link', {
                rel: 'icon',
                href: '/SQLServerLite/img/logo.png',
                type: 'image/png'
            }
        ],
        ['meta', {
                property: 'og:image',
                content: '/SQLServerLite/img/logo-github.png' 
            }
        ],
        ['meta', {
                property: 'og:image:secure_url',
                content: '/SQLServerLite/img/logo-github.png'
            }
        ],
        ['meta', {
                property: 'og:image:width',
                content: '600'
            }
        ],
        ['meta', {
                property: 'og:image:height',
                content: '400'
            }
        ],
        ['meta', {
                property: 'og:title',
                content: 'SQLServerLite'
            }
        ],
        ['meta', {
                property: 'og:description',
                content: 'Effortlessly Convert Numbers to Words in Laravel! 🚀'
            }
        ],
        ['meta', {
                property: 'og:url',
                content: 'https://rmunate.github.io/SQLServerLite/'
            }
        ],
        ['meta', {
                property: 'og:type',
                content: 'website'
            }
        ],
    ],
})
