<?php
    Class PasswordSimple {
        const PASSWORD_BCRYPT = 1;

        public function __construct() {}

        /**
         * Hash the password
         *
         * @param string $password The password to hash
         *
         * @return string|false The hashed password
         */
        function password_hash($password) {
            $options = [
                'cost' => 11,
            ];

            $hash = password_hash($password, PASSWORD_BCRYPT, $options);

            return $hash;
        }

        /**
         * Verify a password against a hash
         *
         * @param string $password The password to verify
         * @param string $hash     The hash to verify against
         *
         * @return boolean If the password matches the hash
         */
        public function password_verify($password, $hash) {

            if (password_verify($password, $hash)) {
                echo 'Password is valid!';
                return true;
            } else {
                echo 'Invalid password.';
                return false;
            }
        }
    }