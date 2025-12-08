/**
 * Validation Rules for Planexx
 * Centralized validation patterns and rules
 * Used across form submission, API validation, and frontend validation
 */

export const validationRules = {
  // Iranian mobile number: 0911111111 or 09111111111
  MOBILE_PATTERN: /^09\d{9}$/,

  // Standard email pattern
  EMAIL_PATTERN: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,

  // OTP: 4 digits only
  OTP_PATTERN: /^\d{4}$/,

  // Digits only
  DIGITS_PATTERN: /^\d+$/,
};

/**
 * Validation functions for Planexx
 */
export const validation = {
  /**
   * Validate Iranian mobile number
   * Accepts: 09XXXXXXXXX (11 digits starting with 09)
   * @param {string} mobile - Mobile number to validate
   * @returns {boolean} True if valid
   */
  validateMobile(mobile) {
    if (!mobile) return false;
    // Remove whitespace
    const cleaned = mobile.trim();
    return validationRules.MOBILE_PATTERN.test(cleaned);
  },

  /**
   * Validate OTP code (4 digits)
   * @param {string} otp - OTP code to validate
   * @returns {boolean} True if valid
   */
  validateOTP(otp) {
    if (!otp) return false;
    return validationRules.OTP_PATTERN.test(otp);
  },

  /**
   * Validate email address
   * @param {string} email - Email to validate
   * @returns {boolean} True if valid
   */
  validateEmail(email) {
    if (!email) return false;
    const cleaned = email.trim().toLowerCase();
    return validationRules.EMAIL_PATTERN.test(cleaned);
  },

  /**
   * Validate required field
   * @param {string} value - Value to check
   * @returns {boolean} True if not empty
   */
  validateRequired(value) {
    return value && value.trim().length > 0;
  },

  /**
   * Validate that value contains only digits
   * @param {string} value - Value to check
   * @returns {boolean} True if only digits
   */
  validateDigitsOnly(value) {
    if (!value) return false;
    return validationRules.DIGITS_PATTERN.test(value);
  },
};
