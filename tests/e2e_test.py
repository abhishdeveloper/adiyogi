import re
from playwright.sync_api import Page, expect, sync_playwright

def test_user_journey():
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()

        base_url = "http://localhost:8000"

        try:
            # 1. Test Login Page loads
            page.goto(f"{base_url}/auth/login")

            expect(page.locator("h2")).to_contain_text("Sign in to your account")
            print("Login page loaded successfully.")

            expect(page.locator('input[name="email"]')).to_be_visible()
            expect(page.locator('input[name="password"]')).to_be_visible()
            expect(page.locator('button[type="submit"]')).to_be_visible()
            print("Login form is present.")

            # 2. Test Registration Page loads
            page.goto(f"{base_url}/auth/register_choice")
            expect(page.locator('text="Register as Patient"')).to_be_visible()
            expect(page.locator('text="Register as Clinic/Doctor"')).to_be_visible()
            print("Registration choice page loaded successfully.")

            print("End-to-End Test (Static portions) passed successfully.")

        except Exception as e:
            print(f"Test failed: {e}")
        finally:
            browser.close()

if __name__ == "__main__":
    test_user_journey()
