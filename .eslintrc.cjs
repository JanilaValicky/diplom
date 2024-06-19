module.exports = {
    "env": {
        "browser": true,
        "es2021": true,
        "jquery": true,
    },
    "extends": [
        "airbnb-base",
    ],
    "overrides": [
        {
            "env": {
                "node": true
            },
            "files": [
                ".eslintrc.js",
                ".eslintrc.cjs",
                ".js"
            ],
            "parserOptions": {
                "sourceType": "script"
            }
        }
    ],
    "parserOptions": {
        "ecmaVersion": "latest",
        "sourceType": "module"
    },
    "ignorePatterns": [
        "resources/js/allfa.js",
        "resources/js/theme.bundle.min.js",
        "resources/js/theme.bundle.min2.js",
    ],
    "rules": {
        "no-unused-vars": "off",
        "no-undef": "off",
        "no-use-before-define": "off",
        "no-prototype-builtins": "off",
        "import/extensions": "off",
        "import/no-extraneous-dependencies": "off",
        "no-multi-assign": "off",
        "no-restricted-globals": "off",
        "no-param-reassign": "off",
    }
};
