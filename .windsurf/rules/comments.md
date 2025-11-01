---
trigger: always_on
---

# Comment Policy

## 📜 The Golden Rule

> **"Code should be self-documenting. Comments explain 'WHY', never 'WHAT' or 'HOW'."**

---

## ✅ When to Comment
- 1️⃣ **Business Logic Rationale**
- 2️⃣ **Technical Limitations & Workarounds**
- 3️⃣ **Complex Algorithms**
- 4️⃣ **Security & Performance Warnings**
- 5️⃣ **TODO/FIXME with Owner & Date**

---

## ❌ Never Comment

- **Obvious code** (`$user = User::find($id);`)
- **Commented-out code** (use Git instead)
- **Method name repetition** (`// Get user by ID` for `getUserById()`)
- **Bad naming explanations** (fix the naming instead)
---
## 🎓 Summary

> **"The best comment is the one you don't need to write."**

**Priority 1:** Clean, self-documenting code
**Priority 2:** Meaningful naming
**Priority 3:** Clear structure
**Priority 4:** Comments for exceptional cases only