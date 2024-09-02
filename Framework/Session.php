<?php 

namespace Framework;

class Session
{
    /**
     * Starts a new session or resumes an existing one.
     *
     * This method checks if a session is already active and starts a new one if not.
     */
    public static function start()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sets a session variable.
     *
     * @param string $key   The key of the session variable.
     * @param mixed  $value The value to be stored in the session.
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a session variable.
     *
     * @param string $key     The key of the session variable to retrieve.
     * @param mixed  $default The default value to return if the key doesn't exist.
     * @return mixed The value of the session variable or the default value.
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Checks if a session variable exists.
     *
     * @param string $key The key of the session variable to check.
     * @return bool True if the session variable exists, false otherwise.
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a specific session variable.
     *
     * @param string $key The key of the session variable to remove.
     */
    public static function clear($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clears all session variables and destroys the session.
     */
    public static function clearAll() {
        session_unset();
        session_destroy();
    }

    
    
    
}
