import { test, expect } from '@playwright/test';

const baseURL = process.env.PLAYWRIGHT_TEST_BASE_URL || 'http://localhost:3000';

test('home page and navigate to section', async ({ page }) => {
  await page.goto(baseURL);
  await page.fill('input[aria-label="Search"]', 'life');
  await page.waitForSelector('text=S.33');
  await page.click('text=S.33');
  await expect(page).toHaveURL(/section\/s33/);
  await page.waitForSelector('text=Right to life');
});
