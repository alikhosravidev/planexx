/**
 * Validation functions for Planexx
 */
export const validation = {

  /**
   * Validate Iranian mobile number
   * @param {string} mobile - Mobile number to validate
   * @returns {boolean} True if valid
   */
  validateMobile(mobile) {
    const re = /^09\d{9}$/;
    return re.test(mobile);
  },

  /**
   * Validate OTP code (4 digits)
   * @param {string} otp - OTP code to validate
   * @returns {boolean} True if valid
   */
  validateOTP(otp) {
    return /^\d{4}$/.test(otp);
  },

  /**
   * Validate email address
   * @param {string} email - Email to validate
   * @returns {boolean} True if valid
   */
  validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  },

  /**
   * Validate required field
   * @param {string} value - Value to check
   * @returns {boolean} True if not empty
   */
  validateRequired(value) {
    return value && value.trim().length > 0;
  }

};
