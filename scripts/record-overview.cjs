const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const VIDEO_DIR = process.env.VIDEO_DIR || '/tmp/opencode/footlocker-video';
const CHROMIUM = process.env.CHROMIUM_PATH || '/usr/bin/chromium';

fs.mkdirSync(VIDEO_DIR, { recursive: true });

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const overlayScript = () => {
    const boot = () => {
        if (document.getElementById('__demo_cursor')) return;

        const style = document.createElement('style');
        style.textContent = `
            #__demo_cursor {
                position: fixed; left: 0; top: 0; width: 30px; height: 36px;
                z-index: 2147483647; pointer-events: none;
                transform: translate(960px, 540px);
                transition: transform .45s cubic-bezier(.22,.61,.36,1);
                filter: drop-shadow(0 3px 4px rgba(0,0,0,.45));
                will-change: transform;
            }
            #__demo_cursor svg { display: block; width: 100%; height: 100%; }
            #__demo_ripple {
                position: fixed; left: 0; top: 0; width: 18px; height: 18px;
                border: 2px solid #dc2626; border-radius: 9999px;
                transform: translate(-50%, -50%) scale(.4); opacity: 0;
                z-index: 2147483646; pointer-events: none;
            }
            #__demo_ripple.show { animation: __demo_ripple .55s ease-out; }
            @keyframes __demo_ripple {
                0% { opacity: .9; transform: translate(-50%,-50%) scale(.4); }
                100% { opacity: 0; transform: translate(-50%,-50%) scale(2.4); }
            }
            #__demo_caption {
                position: fixed; left: 50%; bottom: 34px;
                transform: translateX(-50%) translateY(10px);
                z-index: 2147483647; min-width: 460px; max-width: 1180px;
                background: rgba(9,9,11,.93); color: #fff;
                border-left: 5px solid #dc2626; border-radius: 6px;
                padding: 14px 28px 16px;
                box-shadow: 0 12px 34px rgba(0,0,0,.38);
                pointer-events: none; opacity: 0;
                transition: opacity .25s ease, transform .25s ease;
                font-family: Figtree, ui-sans-serif, system-ui, sans-serif;
            }
            #__demo_caption.show { opacity: 1; transform: translateX(-50%) translateY(0); }
            #__demo_caption .kicker {
                display: block; font-size: 11px; letter-spacing: .2em;
                text-transform: uppercase; color: #f87171; font-weight: 800;
                margin-bottom: 4px;
            }
            #__demo_caption .text {
                display: block; font-size: 19px; line-height: 1.32; font-weight: 600;
            }
        `;
        document.documentElement.appendChild(style);

        const cursor = document.createElement('div');
        cursor.id = '__demo_cursor';
        cursor.innerHTML =
            '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">' +
            '<path d="M4 2.6 20.4 12.4l-7.35 1.12 3.83 7.32-3.3 1.72-3.8-7.45L4 19.6Z" ' +
            'fill="#ffffff" stroke="#111111" stroke-width="1.35" stroke-linejoin="round"/></svg>';

        let x = 960;
        let y = 540;
        try {
            const saved = JSON.parse(localStorage.getItem('__demo_cursor_pos') || 'null');
            if (saved && Number.isFinite(saved.x) && Number.isFinite(saved.y)) {
                x = saved.x;
                y = saved.y;
            }
        } catch (error) {
            /* localStorage unavailable on about:blank */
        }
        cursor.style.transform = `translate(${x}px, ${y}px)`;
        document.documentElement.appendChild(cursor);

        const ripple = document.createElement('div');
        ripple.id = '__demo_ripple';
        document.documentElement.appendChild(ripple);

        const caption = document.createElement('div');
        caption.id = '__demo_caption';
        caption.innerHTML = '<span class="kicker"></span><span class="text"></span>';
        document.documentElement.appendChild(caption);

        window.__demo = {
            setCursor(nx, ny) {
                x = nx;
                y = ny;
                cursor.style.transform = `translate(${nx}px, ${ny}px)`;
                try {
                    localStorage.setItem('__demo_cursor_pos', JSON.stringify({ x: nx, y: ny }));
                } catch (error) {
                    /* ignore */
                }
            },
            ripple() {
                ripple.classList.remove('show');
                void ripple.offsetWidth;
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                ripple.classList.add('show');
            },
            caption(kicker, text) {
                caption.querySelector('.kicker').textContent = kicker;
                caption.querySelector('.text').textContent = text;
                caption.classList.add('show');
            },
            hideCaption() {
                caption.classList.remove('show');
            },
        };
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }
};

(async () => {
    const browser = await chromium.launch({
        headless: true,
        executablePath: CHROMIUM,
        args: ['--hide-scrollbars', '--force-device-scale-factor=1', '--no-sandbox'],
    });

    const context = await browser.newContext({
        viewport: { width: 1920, height: 1080 },
        deviceScaleFactor: 1,
        recordVideo: { dir: VIDEO_DIR, size: { width: 1920, height: 1080 } },
        colorScheme: 'light',
        locale: 'en-PH',
    });

    await context.addInitScript(overlayScript);

    const page = await context.newPage();
    page.setDefaultTimeout(20000);
    const video = page.video();

    const t0 = Date.now();
    const cues = [];
    const addCue = (kicker, text) => {
        cues.push({ start: Date.now() - t0, kicker, text });
    };

    const waitForOverlay = async () => {
        await page.waitForFunction(() => Boolean(window.__demo), null, { timeout: 5000 }).catch(() => {});
    };

    const goto = async (url, wait = 900) => {
        await page.goto(BASE + url, { waitUntil: 'domcontentloaded' });
        await page.waitForLoadState('load').catch(() => {});
        await page
            .evaluate(() =>
                Promise.race([
                    document.fonts ? document.fonts.ready : Promise.resolve(),
                    new Promise((resolve) => setTimeout(resolve, 1500)),
                ])
            )
            .catch(() => {});
        await waitForOverlay();
        await sleep(wait);
    };

    const caption = async (kicker, text, ms = 2600) => {
        await waitForOverlay();
        addCue(kicker, text);
        await page.evaluate(
            ([k, t]) => window.__demo && window.__demo.caption(k, t),
            [kicker, text]
        );
        await sleep(Math.max(ms, 3200));
    };

    const setCursor = async (x, y) => {
        await page.evaluate(
            ([cx, cy]) => window.__demo && window.__demo.setCursor(cx, cy),
            [x, y]
        );
    };

    const cursorTo = async (locator, options = {}) => {
        const target = locator.first();
        if (options.scrollIntoView !== false) {
            await target
                .evaluate((el, block) => el.scrollIntoView({ behavior: 'smooth', block }), options.block || 'center')
                .catch(() => {});
            await sleep(options.scrollWait ?? 650);
        }
        try {
            await target.waitFor({ state: 'visible', timeout: 6000 });
        } catch (error) {
            /* best effort */
        }
        const box = await target.boundingBox();
        if (!box) return null;
        const x = box.x + box.width * (options.rx ?? 0.5);
        const y = box.y + box.height * (options.ry ?? 0.5);
        await setCursor(x, y);
        await page.mouse.move(x, y).catch(() => {});
        await sleep(options.settle ?? 520);
        return { box, x, y };
    };

    const click = async (locator, options = {}) => {
        await cursorTo(locator, options);
        await page.evaluate(() => window.__demo && window.__demo.ripple());
        await locator.first().click();
        await sleep(options.wait ?? 850);
        await waitForOverlay();
    };

    const type = async (locator, text, options = {}) => {
        await cursorTo(locator, options);
        await locator.first().click();
        await locator.first().fill('');
        await locator.first().pressSequentially(text, { delay: options.delay ?? 55 });
        await sleep(options.wait ?? 300);
    };

    const smoothScrollTo = async (y, wait = 900) => {
        await page.evaluate((top) => window.scrollTo({ top, behavior: 'smooth' }), y);
        await sleep(wait);
    };

    const smoothIntoView = async (locator, block = 'center') => {
        await locator
            .first()
            .evaluate((el, b) => el.scrollIntoView({ behavior: 'smooth', block: b }), block)
            .catch(() => {});
        await sleep(800);
    };

    const titleCard = async (kicker, title, subtitle, chips, narration, duration = 4200) => {
        addCue(kicker, narration || subtitle);
        await page.goto('about:blank');
        await page.setContent(`<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    * { box-sizing: border-box; }
    html, body { margin: 0; height: 100%; background: #09090b; color: #fff;
        font-family: Figtree, ui-sans-serif, system-ui, -apple-system, sans-serif; }
    body { display: grid; place-items: center; overflow: hidden; }
    .wrap { width: 100%; max-width: 1400px; padding: 80px 110px; position: relative; }
    .glow { position: absolute; inset: -20% -10% auto auto; width: 760px; height: 760px;
        background: radial-gradient(circle, rgba(220,38,38,.34) 0%, rgba(220,38,38,0) 68%);
        filter: blur(10px); }
    .bar { width: 92px; height: 8px; background: #dc2626; margin-bottom: 30px; }
    .kicker { font-size: 15px; font-weight: 800; letter-spacing: .34em; text-transform: uppercase;
        color: #f87171; margin-bottom: 18px; }
    h1 { margin: 0; font-family: Anton, Impact, 'Arial Narrow', sans-serif; font-weight: 400;
        font-size: 112px; line-height: .94; letter-spacing: .01em; text-transform: uppercase; }
    .sub { margin-top: 24px; font-size: 27px; line-height: 1.4; color: #d4d4d8; max-width: 900px; }
    .chips { display: flex; gap: 12px; margin-top: 46px; flex-wrap: wrap; }
    .chip { border: 1px solid #3f3f46; border-radius: 999px; padding: 10px 18px;
        font-size: 14px; font-weight: 700; letter-spacing: .06em; color: #e4e4e7;
        background: rgba(255,255,255,.03); text-transform: uppercase; }
    .line { position: absolute; left: 110px; right: 110px; bottom: 64px; height: 1px;
        background: linear-gradient(90deg, #dc2626, rgba(220,38,38,0)); }
    .content { animation: rise .7s cubic-bezier(.2,.8,.2,1) both; }
    @keyframes rise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
    #__demo_cursor, #__demo_caption, #__demo_ripple { display: none !important; }
</style>
</head>
<body>
    <div class="wrap">
        <div class="glow"></div>
        <div class="content">
            <div class="bar"></div>
            <div class="kicker">${kicker}</div>
            <h1>${title}</h1>
            <p class="sub">${subtitle}</p>
            <div class="chips">${chips.map((chip) => `<span class="chip">${chip}</span>`).join('')}</div>
        </div>
        <div class="line"></div>
    </div>
</body>
</html>`);
        await sleep(duration);
    };

    try {
        await titleCard(
            'System Overview',
            'Foot Locker PH',
            'A database-driven e-commerce storefront with a complete admin management panel.',
            ['Laravel 13', 'MySQL 8', 'Tailwind CSS', 'Blade + Alpine.js'],
            'Foot Locker PH. A Laravel and MySQL e-commerce system with a customer storefront and a full admin panel.',
            6500
        );

        /* ---------------------------------------------------------------- */
        /* Storefront                                                        */
        /* ---------------------------------------------------------------- */
        await goto('/');
        await caption('Storefront', 'Home page — hero banner, featured drops, brand and category tiles', 3000);
        await cursorTo(page.locator('nav a').filter({ hasText: 'All Shoes' }).first(), { settle: 900 });
        await smoothScrollTo(720);
        await caption('Featured Products', 'Catalog data is pulled live from the MySQL database', 2800);
        await smoothScrollTo(1320);
        await caption('Shop by Brand', 'Every brand tile opens a filtered catalog', 2400);
        await smoothScrollTo(0, 700);

        await click(page.locator('nav a').filter({ hasText: 'All Shoes' }).first());
        await caption('Product Catalog', '27 active products with category, brand, gender and price filters', 2600);

        await cursorTo(page.locator('select[name="brand"]'), { settle: 450 });
        await page.selectOption('select[name="brand"]', 'adidas');
        await sleep(650);
        await cursorTo(page.locator('select[name="gender"]'), { settle: 450 });
        await page.selectOption('select[name="gender"]', 'men');
        await sleep(650);
        await click(page.getByRole('button', { name: 'Apply' }));
        await caption('Filters', 'Brand + gender selected — results update instantly', 2600);

        await smoothScrollTo(0, 500);
        await type(page.locator('input[name="q"]'), 'samba');
        await page.keyboard.press('Enter');
        await page.waitForLoadState('load').catch(() => {});
        await sleep(1000);
        await waitForOverlay();
        await caption('Search Results', 'Searching for “samba” returns matching products', 2400);

        await click(page.locator('a').filter({ hasText: 'adidas Samba OG' }).first());
        await caption('Product Detail', 'Gallery, sizes, per-variant stock, sale pricing, and related items', 3000);
        await smoothIntoView(page.getByRole('link', { name: 'Login to Purchase' }), 'center');
        await sleep(600);
        await click(page.getByRole('link', { name: 'Login to Purchase' }));

        /* ---------------------------------------------------------------- */
        /* Customer account                                                  */
        /* ---------------------------------------------------------------- */
        await caption('Customer Login', 'Sign in with the seeded demo customer account', 2000);
        await type(page.locator('input[name="email"]'), 'customer@footlocker.test');
        await type(page.locator('input[name="password"]'), 'password');
        await click(page.getByRole('button', { name: 'Log in' }), { wait: 1200 });

        await goto('/product/adidas-samba-og', 1100);
        await caption('Signed In', 'Customers can purchase, save favorites, and track orders', 2200);

        await click(page.getByRole('button', { name: /add to wishlist/i }));
        await caption('Wishlist', 'Saved to the customer’s wishlist — stored in the database', 2200);

        const sizeLabel = page
            .locator('input[name="product_variant_id"]:not([disabled])')
            .first()
            .locator('xpath=..');
        await click(sizeLabel);
        await caption('Select Size', 'Per-variant stock is checked before adding to the bag', 1600);
        await smoothIntoView(page.getByRole('button', { name: 'Add to Bag' }), 'center');
        await click(page.getByRole('button', { name: 'Add to Bag' }));

        await caption('Shopping Bag', 'Quantity, subtotal, shipping fee, and total update live', 2600);
        const qty = page.locator('input[name="quantity"]').first();
        await cursorTo(qty, { settle: 450 });
        await qty.fill('2');
        await qty.press('Tab');
        await page.waitForLoadState('load').catch(() => {});
        await sleep(1000);
        await waitForOverlay();
        await caption('Update Quantity', 'Totals recalculate — free shipping over ₱3,000', 2200);

        await click(page.getByRole('link', { name: 'Proceed to Checkout' }));

        await caption('Checkout', 'Delivery details are prefilled from the customer profile', 2600);
        await smoothIntoView(page.getByText('Payment Method').first(), 'center');
        await click(page.getByText('GCash', { exact: true }));
        await caption('Payment Method', 'Cash on Delivery, GCash, or Bank Transfer', 2200);

        await click(page.getByRole('button', { name: 'Place Order' }), { wait: 1400 });

        const bodyText = await page.locator('body').innerText();
        const orderMatch = bodyText.match(/FL-[A-Z0-9]{8}/);
        const orderNumber = orderMatch ? orderMatch[0] : null;
        if (!orderNumber) throw new Error('Could not read the generated order number.');

        await caption('Order Placed', `Unique order number generated: ${orderNumber}`, 3200);
        await smoothScrollTo(260, 700);
        await caption('Order Confirmation', 'Order saved with delivery details and a pending status', 2400);

        /* ---------------------------------------------------------------- */
        /* Tracking + account pages                                          */
        /* ---------------------------------------------------------------- */
        await goto('/track-order');
        await caption('Order Tracking', 'Public tracking — no account required', 1800);
        await type(page.locator('input[name="order_number"]'), orderNumber);
        await click(page.getByRole('button', { name: 'Track' }));
        await caption('Tracking Result', 'Status is pending — waiting for admin confirmation', 2800);

        await goto('/my-orders');
        await caption('My Orders', 'Every order and its current status in one place', 2400);
        await click(page.locator('a').filter({ hasText: orderNumber }).first());
        await caption('Order Detail', 'Items, totals, delivery details, and progress timeline', 2600);

        await goto('/wishlist');
        await caption('Wishlist', 'Saved products persist across sessions', 2400);

        await goto('/profile');
        await caption('Profile', 'Update account information and password', 2400);
        await page.locator('button[title="Account"]').first().hover();
        await sleep(600);
        await click(page.locator('form[action$="/logout"] button').first(), { scrollIntoView: false, settle: 400 });

        /* ---------------------------------------------------------------- */
        /* Admin panel                                                       */
        /* ---------------------------------------------------------------- */
        await goto('/login');
        await caption('Admin Login', 'Administrator account: admin@footlocker.test', 2000);
        await type(page.locator('input[name="email"]'), 'admin@footlocker.test');
        await type(page.locator('input[name="password"]'), 'password');
        await click(page.getByRole('button', { name: 'Log in' }), { wait: 1400 });

        await caption('Admin Dashboard', 'Sales, orders, customers, products, and top-selling items', 3200);
        await smoothScrollTo(520);
        await caption('Live Store Statistics', 'Revenue excludes cancelled orders and updates as orders arrive', 2600);
        await smoothScrollTo(0, 700);

        await click(page.locator('aside').getByRole('link', { name: 'Products', exact: true }));
        await caption('Manage Products', 'Full CRUD for products, images, and size variants', 2600);

        await type(page.locator('input[name="search"]'), 'Vans Old Skool');
        await click(page.getByRole('button', { name: 'Search' }), { wait: 1000 });
        await caption('Search Products', 'Locate a product by name in the admin catalog', 2400);

        const productRow = page
            .locator('tbody tr')
            .filter({ has: page.getByText('Vans Old Skool', { exact: true }) })
            .first();
        await click(productRow.getByRole('link', { name: 'Edit' }));
        await caption('Edit Product', 'Change price, stock, visibility, and variant details', 2400);

        const stockInput = page.locator('input[name="stock"]');
        await smoothIntoView(stockInput);
        await cursorTo(stockInput, { settle: 450 });
        const oldStock = Number(await stockInput.inputValue());
        const newStock = oldStock + 7;
        await stockInput.fill(String(newStock));
        await sleep(700);
        await caption('Update Stock', `Stock changed from ${oldStock} to ${newStock}`, 2000);
        await click(page.getByRole('button', { name: 'Update Product' }), { wait: 1200 });
        await caption('Saved to Database', 'No source code or manual SQL edits required', 2400);

        await goto('/product/vans-old-skool', 1100);
        await caption('Storefront Updated', `The product page now shows In Stock (${newStock})`, 3000);

        await goto('/admin/orders', 1000);
        await caption('Manage Orders', 'View customer orders and update their status', 2600);
        await click(page.locator('tbody tr').filter({ hasText: orderNumber }).first().getByRole('link', { name: 'View' }));
        await caption('Order Detail', 'Customer, items, delivery details, and payment method', 2600);
        await smoothIntoView(page.locator('select[name="status"]'));
        await cursorTo(page.locator('select[name="status"]'), { settle: 450 });
        await page.selectOption('select[name="status"]', 'confirmed');
        await sleep(700);
        await click(page.getByRole('button', { name: 'Save' }), { wait: 1100 });
        await caption('Status Updated', 'Pending → Confirmed saved to the database', 2400);

        await goto('/track-order');
        await type(page.locator('input[name="order_number"]'), orderNumber);
        await click(page.getByRole('button', { name: 'Track' }));
        await caption('Customer Sees the Update', 'Public tracking now shows Confirmed — the change is live', 3000);

        await titleCard(
            'Thank You',
            'Foot Locker PH',
            'Customer storefront + admin management, powered by a normalized MySQL database.',
            ['Storefront', 'Cart + Checkout', 'Order Tracking', 'Admin CRUD'],
            'Thank you for watching. Foot Locker PH, customer storefront and admin management, powered by a normalized MySQL database.',
            7000
        );

        await context.close();
        await browser.close();

        const rawVideo = await video.path();
        fs.writeFileSync(path.join(VIDEO_DIR, 'cues.json'), JSON.stringify(cues, null, 2));
        console.log(`VIDEO:${rawVideo}`);
    } catch (error) {
        console.error('RECORDING_FAILED:', error);
        await page.screenshot({ path: path.join(VIDEO_DIR, 'failure.png'), fullPage: false }).catch(() => {});
        await context.close().catch(() => {});
        await browser.close().catch(() => {});
        process.exit(1);
    }
})();
