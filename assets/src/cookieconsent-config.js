import 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.umd.js';

let footer = "";
if(cookiesconsents.privacypolicy.guid) footer += `<a href="${cookiesconsents.privacypolicy.guid || "#"}">${cookiesconsents.privacypolicy.post_title || "Privacy Policy"}</a>`;
if(cookiesconsents.termsandconditions.guid) footer += ` <a href="${cookiesconsents.termsandconditions.guid || "#"}">${cookiesconsents.termsandconditions.post_title || "Terms and conditions"}</a>`

CookieConsent.run({
    guiOptions: {
        consentModal: {
            layout: "box",
            position: "bottom left",
            equalWeightButtons: true,
            flipButtons: false
        },
        preferencesModal: {
            layout: "box",
            position: "right",
            equalWeightButtons: true,
            flipButtons: false
        }
    },
    categories: {
        necessary: {
            readOnly: true
        },
        analytics: {}
    },
    language: {
        default: "en",
        autoDetect: "browser",
        translations: {
            en: {
                consentModal: {
                    title: `${cookiesconsents.title || "Hello traveller, it's cookie time!"}`,
                    description: `${cookiesconsents.description}`,
                    acceptAllBtn: `${cookiesconsents.acceptallbtn || "Accept all"}`,
                    acceptNecessaryBtn: `${cookiesconsents.acceptnecessarybtn || "Reject all"}`,
                    showPreferencesBtn: `${cookiesconsents.showpreferencesbtn || "Manage preferences"}`,
                    footer: footer.trim()
                },
                preferencesModal: {
                    title: "Consent Preferences Center",
                    acceptAllBtn: `${cookiesconsents.acceptallbtn || "Accept all"}`,
                    acceptNecessaryBtn: `${cookiesconsents.acceptnecessarybtn || "Reject all"}`,
                    savePreferencesBtn: `${cookiesconsents.savepreferencesbtn || "Save preferences"}`,
                    closeIconLabel: "Close modal",
                    serviceCounterLabel: "Service|Services",
                    sections: [
                        {
                            title: "Cookie Usage",
                            description: `${cookiesconsents.cookieusage || "Cookies are small data files stored on your device when browsing a website. They are used to enhance the user experience, retain preferences, and gather information about the site’s performance and functionality. Cookies can be divided into different categories based on their usage."}`
                        },
                        {
                            title: "Strictly Necessary Cookies <span class=\"pm__badge\">Always Enabled</span>",
                            description: `${cookiesconsents.necessarycookies || "These cookies are essential for the proper functioning of the website. They enable basic features such as page navigation and access to secure areas. Without these cookies, the site cannot function correctly. They do not store personally identifiable information and cannot be disabled."}`,
                            linkedCategory: "necessary"
                        },
                        {
                            title: "Analytics Cookies",
                            description: `${cookiesconsents.analyticscookies || "Analytics cookies collect information about how visitors use a website, such as the pages viewed and links clicked. This data is used to improve the content and performance of the site. The information gathered is typically anonymized and used for statistical purposes."}`,
                            linkedCategory: "analytics"
                        },
                        {
                            title: "More information",
                            description: `For any query in relation to my policy on cookies and your choices, please <a class="cc__link" href="mailto:${cookiesconsents.mail}">contact me</a>.`
                        }
                    ]
                }
            }
        }
    }
});