# Prototype Analysis Report

**Date**: 2025-11-27  
**Project**: Planexx  
**Analyzed Path**: `docs/prototypes/`

---

## 📋 Overview

This document provides a detailed analysis of all UI prototypes, their features, components, and technical specifications to guide the implementation process.

---

## 🎨 Design System Analysis

### Color Palette

**Primary Colors**:
- Primary: `#0f172a` (slate-900) - Main brand color
- Secondary: Various accent colors per module

**Accent Colors by Module**:
- **Authentication**: Blue (`#3b82f6`)
- **Dashboard**: Slate/Gray gradients
- **Organization**: Blue, Purple, Teal, Orange
- **Knowledge Base**: Yellow, Teal, Indigo, Purple
- **Documents**: Sky, Amber, Green
- **Mobile App**: Slate, Amber, Sky, Purple, Green, Rose

### Typography

**Font Family**: Sahel 3.4.0 (Persian web font)

**Font Sizes** (Tailwind classes):
- `text-xs`: 12px - Small labels, badges
- `text-sm`: 14px - Secondary text
- `text-base`: 16px - Body text
- `text-lg`: 18px - Subheadings
- `text-xl`: 20px - Card titles
- `text-2xl`: 24px - Section headings
- `text-3xl`: 30px - Page titles
- `text-4xl`: 36px - Hero text

### Spacing System

**Consistent spacing** using Tailwind's spacing scale:
- `gap-2`: 8px - Tight spacing
- `gap-3`: 12px - Default spacing
- `gap-4`: 16px - Comfortable spacing
- `gap-6`: 24px - Section spacing
- `gap-8`: 32px - Large spacing
- `p-4`: 16px - Card padding
- `p-6`: 24px - Section padding

### Border Radius

**Modern, rounded design**:
- `rounded-lg`: 8px - Small elements
- `rounded-xl`: 12px - Medium elements
- `rounded-2xl`: 16px - Cards
- `rounded-3xl`: 24px - Large cards, hero sections

### Shadows

**Elevation system**:
- `shadow-sm`: Subtle elevation
- `shadow-md`: Default cards
- `shadow-lg`: Elevated cards, modals
- `shadow-xl`: Floating elements

---

## 📱 Component Inventory

### 1. Layout Components

#### Header Components

**Dashboard Header** (`_components/dashboard-header.php`):
- User profile dropdown
- Notifications bell with badge
- Search bar
- Breadcrumb navigation
- Action buttons area

**Module Header** (`_components/module-header.php`):
- Page title
- Breadcrumbs
- Action buttons (configurable)
- Stats summary (optional)

#### Sidebar Components

**Dashboard Sidebar** (`_components/dashboard-sidebar.php`):
- Logo and brand
- Navigation menu with icons
- Active state highlighting
- Collapsible sections
- User info at bottom

**Module-Specific Sidebars**:
- `org-sidebar.php` - Organization module
- `knowledge-sidebar.php` - Knowledge base module
- `documents-sidebar.php` - Documents module

**Features**:
- Hierarchical navigation
- Icon + text labels
- Active state styling
- Hover effects
- Badge counts for notifications

#### Bottom Navigation (`_components/app-bottom-nav.php`)

**Mobile PWA navigation**:
- 4 main tabs: Home, Personalized, Analytics, Profile
- Fixed position at bottom
- Active state with background
- Icon + text labels

### 2. Card Components

#### Stat Card

**Usage**: Display metrics and statistics

**Variants**:
1. **Simple Stat Card**:
   - Icon
   - Value (large number)
   - Label
   - Change indicator (+/- percentage)
   - Color-coded by type

2. **Gradient Stat Card**:
   - Gradient background
   - White text
   - Icon
   - Large value
   - Description

**Example Structure**:
```html
<div class="bg-white border border-gray-200 rounded-2xl p-6">
  <div class="flex items-center justify-between mb-4">
    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
      <i class="fa-solid fa-users text-blue-600 text-xl"></i>
    </div>
    <span class="text-green-600 text-sm font-medium">+12%</span>
  </div>
  <h3 class="text-3xl font-bold text-gray-900 mb-1">247</h3>
  <p class="text-sm text-gray-600">تعداد کاربران</p>
</div>
```

#### Quick Access Card

**Usage**: Module shortcuts on dashboard

**Features**:
- Large icon with colored background
- Module title
- Short description
- Hover effects (scale, shadow)
- Disabled state for unavailable modules

**Example Structure**:
```html
<a href="/module" class="group bg-white border rounded-2xl p-6 hover:shadow-lg transition-all">
  <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-4">
    <i class="fa-solid fa-users text-white text-2xl"></i>
  </div>
  <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600">
    مدیریت کاربران
  </h3>
  <p class="text-sm text-gray-600">
    مدیریت کاربران، دپارتمان‌ها و موقعیت‌های شغلی
  </p>
</a>
```

#### Content Card

**Usage**: Display list items (experiences, documents, users)

**Features**:
- Header with title and metadata
- Content area
- Footer with actions
- Badge/tag support
- Status indicators

### 3. Form Components

#### Input Fields

**Text Input**:
```html
<div class="mb-4">
  <label class="block text-sm font-medium text-gray-700 mb-2">
    عنوان
  </label>
  <input 
    type="text" 
    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
    placeholder="عنوان را وارد کنید"
  >
</div>
```

**Select Dropdown**:
```html
<select class="w-full px-4 py-3 border border-gray-300 rounded-xl">
  <option>انتخاب کنید</option>
  <option>گزینه 1</option>
</select>
```

**Textarea**:
```html
<textarea 
  class="w-full px-4 py-3 border border-gray-300 rounded-xl resize-none"
  rows="4"
></textarea>
```

**Checkbox**:
```html
<label class="flex items-center gap-2 cursor-pointer">
  <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-blue-600">
  <span class="text-sm text-gray-700">متن چک‌باکس</span>
</label>
```

#### Button Variants

**Primary Button**:
```html
<button class="bg-slate-900 text-white px-6 py-3 rounded-xl font-medium hover:bg-slate-800 transition-all">
  ذخیره
</button>
```

**Secondary Button**:
```html
<button class="bg-white text-slate-900 border border-slate-300 px-6 py-3 rounded-xl font-medium hover:bg-slate-50">
  انصراف
</button>
```

**Danger Button**:
```html
<button class="bg-red-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-red-700">
  حذف
</button>
```

**Icon Button**:
```html
<button class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100">
  <i class="fa-solid fa-ellipsis-vertical"></i>
</button>
```

### 4. Navigation Components

#### Breadcrumb

**Features**:
- Clickable path items
- Separator icons
- Last item non-clickable
- Responsive (collapse on mobile)

**Example**:
```html
<nav class="flex items-center gap-2 text-sm">
  <a href="/" class="text-gray-600 hover:text-gray-900">خانه</a>
  <i class="fa-solid fa-chevron-left text-gray-400 text-xs"></i>
  <a href="/module" class="text-gray-600 hover:text-gray-900">ماژول</a>
  <i class="fa-solid fa-chevron-left text-gray-400 text-xs"></i>
  <span class="text-gray-900 font-medium">صفحه فعلی</span>
</nav>
```

#### Tabs

**Usage**: Switch between views

**Example**:
```html
<div class="border-b border-gray-200">
  <nav class="flex gap-8">
    <button class="pb-4 border-b-2 border-blue-600 text-blue-600 font-medium">
      تب فعال
    </button>
    <button class="pb-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900">
      تب غیرفعال
    </button>
  </nav>
</div>
```

### 5. Feedback Components

#### Badge

**Variants**:
- Status badges (success, warning, danger, info)
- Count badges (notifications)
- Tag badges (categories, labels)

**Examples**:
```html
<!-- Status Badge -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-green-100 text-green-800">
  منتشر شده
</span>

<!-- Count Badge -->
<span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
  3
</span>

<!-- Tag Badge -->
<span class="inline-flex items-center px-2 py-1 rounded-lg text-xs bg-blue-50 text-blue-700">
  #تگ
</span>
```

#### Toast Notification

**Features**:
- Auto-dismiss after 3 seconds
- Success, error, warning, info variants
- Icon + message
- Close button
- Slide-in animation

#### Modal

**Features**:
- Backdrop overlay
- Centered content
- Header with title and close button
- Body content area
- Footer with actions
- Responsive sizing

### 6. Data Display Components

#### Table

**Features**:
- Responsive design
- Sortable columns
- Row actions (edit, delete)
- Checkbox for bulk selection
- Pagination
- Empty state

**Example Structure**:
```html
<div class="overflow-x-auto">
  <table class="w-full">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
          عنوان
        </th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 text-sm text-gray-900">
          محتوا
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

#### Pagination

**Features**:
- Previous/Next buttons
- Page numbers
- Current page highlighting
- Jump to first/last
- Items per page selector

#### Empty State

**Features**:
- Icon
- Heading
- Description
- Call-to-action button

---

## 📄 Page Analysis

### 1. Authentication (`auth.php`)

**Purpose**: Mobile OTP login

**Flow**:
1. Enter mobile number (09XXXXXXXXX)
2. Click "دریافت کد"
3. Show OTP input (4 digits)
4. Auto-submit when 4 digits entered
5. Show countdown timer (60s)
6. Allow resend after timer expires
7. Allow back to change mobile

**Features**:
- Mobile validation (09 + 9 digits)
- OTP auto-focus next input
- Paste support for OTP
- Loading states
- Error messages
- Countdown timer
- Resend OTP button

**Design**:
- Full-screen centered layout
- Minimal design
- Large, touch-friendly inputs
- Clear visual hierarchy
- RTL support

**Technical Notes**:
- No labels attached to inputs (placeholder only)
- Center-aligned text
- Mobile-first responsive
- Smooth transitions

---

### 2. Main Dashboard (`dashboard/index.php`)

**Purpose**: Central hub for all modules

**Sections**:

1. **Header**:
   - User info and avatar
   - Notifications bell
   - Search bar
   - Profile dropdown

2. **Stats Row** (4 cards):
   - Total users
   - Active employees
   - Departments
   - Job positions

3. **Quick Access** (Module cards):
   - Organization structure
   - Knowledge base
   - Documents
   - BPMS
   - Form engine
   - Notifications

4. **Recent Activity**:
   - Timeline of recent actions
   - User avatars
   - Timestamps
   - Action descriptions

5. **Sidebar**:
   - Logo
   - Main navigation
   - Module links
   - User info at bottom

**Data Requirements**:
- User stats
- Module access permissions
- Recent activities feed
- Notification count

---

### 3. Organization Module (`dashboard/org/`)

#### Module Dashboard (`org/index.php`)

**Stats** (4 cards):
- Total users: 247
- Active employees: 189
- Departments: 24
- Job positions: 38

**Quick Access** (4 cards):
- User management
- Departments
- Job positions
- Roles & permissions

**Recent Users Table**:
- Name, department, position, status
- Actions: view, edit, delete

#### User Management (`org/users/`)

**List Page** (`list.php`):
- Search bar
- Filters: department, position, status
- User table with columns:
  - Avatar + name
  - Mobile
  - Email
  - Department
  - Position
  - Status
  - Actions
- Pagination
- Bulk actions

**Create/Edit Form** (`create.php`, `edit.php`):
- Personal info: first name, last name, national code
- Contact: mobile, email
- Organization: department, position
- Status: active/inactive
- Avatar upload
- Save/Cancel buttons

#### Department Management (`org/departments/`)

**List Page**:
- Hierarchical tree view
- Expand/collapse nodes
- Department info: name, manager, employee count
- Actions: add child, edit, delete
- Drag-and-drop for reordering

**Create/Edit Form**:
- Department name
- Parent department (dropdown)
- Manager (user selector)
- Description
- Sort order

#### Job Positions (`org/job-positions/`)

**List Page**:
- Position table
- Columns: title, department, employee count, status
- Search and filters

**Create/Edit Form**:
- Position title
- Department
- Description
- Responsibilities (textarea)
- Requirements (textarea)

#### Roles & Permissions (`org/roles-permissions/`)

**Roles Page**:
- Role list
- Columns: name, description, user count
- Actions: edit permissions, delete

**Permissions Matrix**:
- Modules in rows
- Actions in columns (view, create, edit, delete)
- Checkboxes for each permission
- Save button

---

### 4. Knowledge Base Module (`dashboard/knowledge/`)

#### Module Dashboard (`knowledge/index.php`)

**Stats** (4 cards):
- Total experiences: 184
- This month: 38
- Templates: 12
- Active departments: 8

**Quick Access** (4 cards):
- Manage experiences
- Experience templates
- Create new experience
- Reports (disabled)

**Recent Experiences Table**:
- Title, department, template, author, date, status
- Actions: view, edit, delete

#### Experience Management (`knowledge/experiences/`)

**List Page** (`list.php`):
- Search bar
- Filters: department, template, status, date range
- Experience cards with:
  - Title
  - Department badge
  - Template badge
  - Author info
  - Date
  - Status badge
  - View/like/comment counts
  - Actions dropdown
- Pagination

**Create Form** (`create.php`):
- Title
- Template selector (affects dynamic fields)
- Category
- Department
- Tags (multi-select or input)
- Dynamic fields based on template
- Content editor (rich text)
- Attachments
- Status (draft/published)
- Save/Cancel buttons

**Features**:
- Dynamic form fields based on selected template
- Tag autocomplete
- File upload
- Rich text editor
- Auto-save draft

#### Template Management (`knowledge/templates/`)

**List Page** (`list.php`):
- Template cards
- Info: name, description, field count, usage count
- Actions: edit, duplicate, delete
- Create new template button

**Create/Edit Form** (`create.php`):
- Template name
- Description
- Form builder interface:
  - Drag-and-drop field types
  - Field properties panel
  - Field types: text, textarea, select, checkbox, radio, date, file
  - Field validation rules
  - Preview mode
- Save template

**Form Builder Features**:
- Drag-and-drop interface
- Field type palette
- Field configuration:
  - Label
  - Placeholder
  - Required/optional
  - Validation rules
  - Help text
- Field reordering
- Field duplication
- Field deletion
- Live preview

---

### 5. Documents Module (`dashboard/documents/`)

#### Main Page (`index.php`)

**Layout**:
- Left sidebar: folder tree
- Main area: file browser
- Right sidebar: file details (when selected)

**Features**:
- Folder navigation
- File upload (drag-and-drop)
- File preview
- File actions: download, share, move, rename, delete
- Bulk selection
- Search files
- Sort options
- View modes: grid, list

**File Types**:
- Documents (PDF, DOC, DOCX)
- Images (JPG, PNG, GIF)
- Spreadsheets (XLS, XLSX)
- Presentations (PPT, PPTX)
- Archives (ZIP, RAR)

#### Favorites (`favorites.php`)

**Features**:
- List of favorited files
- Quick access
- Same actions as main page

#### Recent Files (`recent.php`)

**Features**:
- Recently accessed files
- Sorted by access time
- Quick access

#### Temporary Files (`temporary.php`)

**Features**:
- Files in trash
- Restore or permanently delete
- Auto-delete after 30 days

---

### 6. Mobile PWA (`app/`)

#### Home Page (`index.php`)

**Header**:
- User avatar and name
- Notification bell with badge
- Stats cards (points, level, streak days)

**Sections**:
1. **News & Announcements**:
   - Card list
   - Unread badge
   - Pin indicator
   - Date and author

2. **Knowledge Base**:
   - Recent experiences
   - Tags
   - Like/comment counts

3. **Tasks**:
   - Pending tasks
   - Priority badges (urgent, medium, normal)
   - Due dates

**Bottom Navigation**:
- Home (active)
- Personalized
- Analytics
- Profile

#### Personalized Feed (`personalized.php`)

**Header**:
- User's interest tags (from onboarding)
- Edit interests button

**Sections**:
1. **Personalized News**:
   - Based on selected tags
   - Relevance score

2. **Related Experiences**:
   - Matching user's tags
   - Recommended badge

3. **Suggested Courses**:
   - Based on job position
   - Progress indicator

4. **Suggested Events**:
   - Webinars, workshops
   - Registration button

#### Analytics (`analytics.php`)

**Gamification Dashboard**:

1. **Progress to Next Level**:
   - Current level badge
   - Progress bar
   - Points needed
   - Current rank

2. **Weekly Chart**:
   - Points earned per day (last 7 days)
   - Bar chart

3. **Stats by Category**:
   - Experiences: count + points
   - Courses: count + points
   - Tasks: count + points
   - Interactions: count + points

4. **Achievements**:
   - Achievement cards
   - Progress bars
   - Unlock conditions
   - Locked/unlocked states

5. **Recent Activity**:
   - Timeline of actions
   - Points earned per action
   - Timestamps

#### Profile (`profile.php`)

**Sections**:

1. **User Info**:
   - Avatar
   - Name
   - Position
   - Department
   - Employee ID

2. **Activity Stats**:
   - Experiences shared
   - Courses completed
   - Tasks done
   - Comments posted

3. **Contact Info**:
   - Email (non-editable)
   - Mobile (non-editable)

4. **Account Settings**:
   - Change password
   - Notification preferences
   - Language
   - Theme

5. **Level Timeline**:
   - Visual timeline of level progression
   - Dates of level-ups
   - Points at each level

6. **Logout Button**:
   - Red button
   - Confirmation modal

#### Onboarding (`onboarding.php`)

**3-Step Flow**:

**Step 1: Welcome**:
- App logo
- Welcome message
- Feature highlights
- Next button

**Step 2: Select Interests**:
- 6 categories with tags
- Multi-select (minimum 3)
- Categories:
  - Technology
  - Management
  - Marketing
  - Finance
  - HR
  - Operations
- Next button (disabled until 3 selected)

**Step 3: Notification Settings**:
- Toggle switches:
  - News notifications
  - Experience notifications
  - Task reminders
  - Achievement notifications
- Finish button

**Features**:
- Progress indicator (dots)
- Back button (except step 1)
- Skip button (optional)
- Save to localStorage

#### PWA Features

**Manifest** (`manifest.json.php`):
- App name: ساپل
- Short name: ساپل
- Display: standalone
- Theme color: #0f172a
- Background: #ffffff
- Icons: 192x192, 512x512
- Start URL: /app/
- Shortcuts to main pages

**Service Worker** (`service-worker.js`):
- Cache strategy: Cache First
- Cached assets:
  - HTML pages
  - CSS files
  - JS files
  - Fonts
  - Icons
- Offline fallback page
- Auto-update on new version

---

## 🔧 JavaScript Functionality

### Utilities (`assets/js/utils.js`)

**Functions**:

1. **validateMobile(mobile)**:
   - Check format: 09XXXXXXXXX
   - Return boolean

2. **showToast(message, type)**:
   - Display toast notification
   - Types: success, error, warning, info
   - Auto-dismiss after 3s

3. **debounce(func, delay)**:
   - Debounce function calls
   - Used for search inputs

4. **formatDate(date)**:
   - Format date to Persian
   - Return string

5. **formatNumber(number)**:
   - Convert to Persian digits
   - Add thousand separators

### App Logic (`assets/js/app.js`)

**Features**:

1. **OTP Input Handler**:
   - Auto-focus next input
   - Backspace to previous
   - Paste support
   - Auto-submit when complete

2. **Countdown Timer**:
   - 60-second countdown
   - Enable resend button when done
   - Format: MM:SS

3. **Form Validation**:
   - Real-time validation
   - Error message display
   - Disable submit until valid

4. **File Upload**:
   - Drag-and-drop support
   - Preview images
   - Progress bar
   - File size validation

5. **Search Autocomplete**:
   - Debounced search
   - Show suggestions
   - Keyboard navigation

6. **Modal Management**:
   - Open/close modals
   - Backdrop click to close
   - ESC key to close
   - Focus trap

7. **Dropdown Menus**:
   - Toggle on click
   - Close on outside click
   - Keyboard navigation

---

## 📊 Data Structures

### User Object
```json
{
  "id": 1,
  "first_name": "احمد",
  "last_name": "محمدی",
  "mobile": "09123456789",
  "email": "ahmad@example.com",
  "avatar": "/avatars/user1.jpg",
  "department": {
    "id": 5,
    "name": "فناوری اطلاعات"
  },
  "job_position": {
    "id": 12,
    "title": "توسعه‌دهنده ارشد"
  },
  "status": "active",
  "employee_id": "EMP-001",
  "points": 1250,
  "level": "نقره‌ای",
  "rank": 15
}
```

### Experience Object
```json
{
  "id": 1,
  "title": "بهینه‌سازی فرآیند قراردادها",
  "content": {
    "problem": "...",
    "solution": "...",
    "results": "..."
  },
  "template": {
    "id": 3,
    "name": "تجربه قراردادی"
  },
  "category": {
    "id": 2,
    "name": "مالی"
  },
  "department": {
    "id": 5,
    "name": "مالی"
  },
  "author": {
    "id": 10,
    "name": "احمد باقری",
    "avatar": "/avatars/user10.jpg"
  },
  "tags": ["قرارداد", "بهینه‌سازی", "اتوماسیون"],
  "status": "published",
  "views_count": 45,
  "likes_count": 12,
  "comments_count": 5,
  "created_at": "2024-11-20T10:30:00Z"
}
```

### Template Object
```json
{
  "id": 1,
  "name": "تجربه قراردادی",
  "description": "قالب برای ثبت تجربیات مرتبط با قراردادها",
  "fields": [
    {
      "type": "text",
      "name": "problem",
      "label": "مشکل یا چالش",
      "required": true,
      "validation": "max:200"
    },
    {
      "type": "textarea",
      "name": "solution",
      "label": "راه‌حل پیاده‌سازی شده",
      "required": true,
      "rows": 5
    },
    {
      "type": "textarea",
      "name": "results",
      "label": "نتایج و دستاوردها",
      "required": false,
      "rows": 3
    }
  ],
  "usage_count": 23,
  "is_active": true
}
```

### Department Object
```json
{
  "id": 1,
  "name": "فناوری اطلاعات",
  "parent_id": null,
  "manager": {
    "id": 5,
    "name": "علی رضایی"
  },
  "employee_count": 15,
  "sort_order": 1,
  "children": [
    {
      "id": 2,
      "name": "توسعه نرم‌افزار",
      "parent_id": 1,
      "employee_count": 8
    }
  ]
}
```

---

## 🎯 Implementation Priorities

### Critical (Must Have - Phase 1-3)
1. ✅ Design system setup
2. ✅ Component library
3. ✅ Authentication UI
4. ✅ Dashboard layout
5. ✅ Organization module UI

### High (Should Have - Phase 4-6)
1. ⚠️ Knowledge base module (new)
2. ⚠️ Mobile PWA
3. ⚠️ Search functionality
4. ⚠️ Notification system

### Medium (Nice to Have - Phase 7-8)
1. 📋 Documents module
2. 📋 Gamification system
3. 📋 Advanced analytics
4. 📋 Reporting

### Low (Future Enhancement)
1. 💡 Real-time chat
2. 💡 Video conferencing
3. 💡 Advanced permissions
4. 💡 Multi-language support

---

## 🚀 Technical Recommendations

### 1. Component Architecture

**Use Blade Components with Props**:
```blade
{{-- Define component --}}
@props([
    'title',
    'value',
    'change' => null,
    'icon' => 'fa-chart-line',
    'color' => 'blue'
])

<div class="bg-white rounded-2xl p-6">
  {{-- Component content --}}
</div>

{{-- Usage --}}
<x-stat-card 
  title="تعداد کاربران" 
  value="247" 
  change="+12%" 
  icon="fa-users"
  color="blue"
/>
```

### 2. State Management

**Use Alpine.js for Interactive Components**:
```html
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open">Content</div>
</div>
```

### 3. API Integration

**Use Axios with AJAX System v2.0**:
```javascript
// Declarative approach (preferred)
<form data-ajax action="/api/users" method="POST">
  <!-- Form fields -->
</form>

// Programmatic approach (when needed)
axios.post('/api/users', data)
  .then(response => {
    // Handle success
  })
  .catch(error => {
    // Handle error
  });
```

### 4. Performance Optimization

**Lazy Loading**:
- Use Intersection Observer for images
- Lazy load components below fold
- Code splitting for large modules

**Caching**:
- Cache API responses (Redis)
- Cache rendered views
- Service worker caching for PWA

### 5. Accessibility

**WCAG 2.1 AA Compliance**:
- Semantic HTML
- ARIA labels
- Keyboard navigation
- Focus management
- Color contrast
- Screen reader support

---

## 📝 Notes for Developers

### Do's ✅
- Follow existing architecture patterns
- Use BaseWebController for admin panels
- Implement API-first approach
- Write tests for all features
- Use Blade components for reusability
- Follow Tailwind utility-first approach
- Maintain responsive design
- Add loading states
- Handle errors gracefully
- Document complex logic

### Don'ts ❌
- Don't access database directly from views
- Don't use inline styles
- Don't hardcode values
- Don't skip validation
- Don't ignore accessibility
- Don't forget mobile responsiveness
- Don't skip error handling
- Don't commit commented code
- Don't use magic numbers
- Don't ignore security best practices

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-27  
**Status**: Complete
