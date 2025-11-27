# Prototype Implementation Plan

**Date**: 2025-11-27  
**Project**: Planexx - Laravel 12 Modular Application  
**Scope**: Integration of UI Prototypes into Production Architecture

---

## 📋 Executive Summary

This document provides a comprehensive roadmap for implementing the UI prototypes located in `docs/prototypes/` into the Laravel production codebase while maintaining architectural integrity and following established patterns.

### Prototype Overview

**Location**: `docs/prototypes/`

**Components**:
1. **Authentication** (`auth.php`) - Mobile OTP login
2. **Dashboard** (`dashboard/index.php`) - Main admin dashboard
3. **Organization Module** (`dashboard/org/`) - User, department, job position management
4. **Knowledge Base Module** (`dashboard/knowledge/`) - Experience management system
5. **Documents Module** (`dashboard/documents/`) - Document management
6. **Mobile PWA** (`app/`) - Progressive Web App for employees

**Technology Stack (Prototypes)**:
- PHP (static prototypes)
- Tailwind CSS 3.x (CDN)
- Font Awesome 6.5.1
- Sahel Font 3.4.0
- Vanilla JavaScript

---

## 🎯 Strategic Goals

1. **Preserve Architecture**: Maintain Laravel 12 modular architecture with DDD principles
2. **API-First Approach**: All data access via APIs (no direct DB calls from frontend)
3. **Reusable Components**: Convert PHP prototypes to Blade components
4. **Progressive Enhancement**: Implement features incrementally
5. **Testing Coverage**: Maintain test coverage throughout implementation
6. **Documentation**: Update documentation as features are implemented

---

## 📊 Gap Analysis

### Current State

**Backend (Production)**:
- ✅ Laravel 12 with Hybrid Modular Architecture
- ✅ Core Modules: User, Organization, FormEngine, BPMS
- ✅ Repository Pattern with Criteria
- ✅ Service Layer for business logic
- ✅ API authentication (API Key)
- ✅ Transformer system for responses
- ✅ AJAX System v2.0 (refactored, modular)

**Frontend (Production)**:
- ✅ Vite + Laravel Mix
- ✅ Tailwind CSS configured
- ✅ AJAX System v2.0 (declarative, secure)
- ✅ BaseWebController for admin panels
- ⚠️ Limited Blade components
- ⚠️ No comprehensive admin UI
- ❌ No mobile PWA

**Prototypes (Static)**:
- ✅ Complete UI designs
- ✅ Responsive layouts
- ✅ Modern design system
- ✅ Component-based structure
- ❌ Not integrated with backend
- ❌ Static data only

### Gap Summary

| Feature | Prototype | Backend | Frontend | Priority |
|---------|-----------|---------|----------|----------|
| **Authentication UI** | ✅ | ✅ | ❌ | HIGH |
| **Dashboard Layout** | ✅ | Partial | ❌ | HIGH |
| **Organization Module UI** | ✅ | ✅ | Partial | HIGH |
| **Knowledge Base Module** | ✅ | ❌ | ❌ | MEDIUM |
| **Documents Module** | ✅ | ❌ | ❌ | LOW |
| **Mobile PWA** | ✅ | Partial | ❌ | MEDIUM |
| **Gamification System** | ✅ | ❌ | ❌ | LOW |

---

## 🗺️ Implementation Roadmap

### Phase 1: Foundation & Infrastructure (Week 1-2)

**Goal**: Establish frontend architecture and component library

#### 1.1 Design System Setup
- [ ] Extract Tailwind configuration from prototypes
- [ ] Create `tailwind.config.js` with prototype design tokens
- [ ] Set up CSS variables in `resources/css/variables.css`
- [ ] Configure Vite for asset compilation
- [ ] Set up Sahel font integration

**Files to Create**:
```
resources/
├── css/
│   ├── variables.css          # Design tokens from prototypes
│   ├── components.css         # Component-specific styles
│   └── app.css               # Main stylesheet
├── js/
│   ├── utils/
│   │   ├── validation.js     # From prototype utils.js
│   │   ├── toast.js          # Toast notifications
│   │   └── helpers.js        # General helpers
│   └── app.js                # Main JS entry
```

#### 1.2 Blade Component Library
- [ ] Create base layout components
- [ ] Convert prototype components to Blade

**Components to Create**:
```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php              # Main app layout
│   │   ├── dashboard.blade.php        # Dashboard layout
│   │   └── auth.blade.php             # Auth layout
│   ├── dashboard/
│   │   ├── header.blade.php           # From dashboard-header.php
│   │   ├── sidebar.blade.php          # From dashboard-sidebar.php
│   │   ├── stat-card.blade.php        # Reusable stat card
│   │   └── quick-access.blade.php     # Quick access module cards
│   ├── ui/
│   │   ├── button.blade.php           # Reusable button
│   │   ├── card.blade.php             # Reusable card
│   │   ├── badge.blade.php            # Badge component
│   │   ├── breadcrumb.blade.php       # Breadcrumb navigation
│   │   └── modal.blade.php            # Modal component
│   └── forms/
│       ├── input.blade.php            # Text input
│       ├── select.blade.php           # Select dropdown
│       ├── textarea.blade.php         # Textarea
│       └── checkbox.blade.php         # Checkbox
```

#### 1.3 AJAX System Integration
- [ ] Verify AJAX System v2.0 compatibility with new UI
- [ ] Create form examples using declarative AJAX
- [ ] Test security features (CSRF, HttpOnly cookies)
- [ ] Document integration patterns

**Reference**: `.claude/ajax-system-overview.md`

---

### Phase 2: Authentication & Authorization (Week 3)

**Goal**: Implement mobile OTP authentication UI

#### 2.1 Backend Verification
- [ ] Verify OTP authentication API endpoints exist
- [ ] Test API responses match frontend expectations
- [ ] Ensure proper error handling

**Existing APIs to Verify**:
```
POST /api/v1/auth/request-otp
POST /api/v1/auth/verify-otp
POST /api/v1/auth/logout
```

#### 2.2 Frontend Implementation
- [ ] Create authentication routes
- [ ] Implement login page (from `auth.php` prototype)
- [ ] Implement OTP verification UI
- [ ] Add form validation
- [ ] Integrate with AJAX System v2.0
- [ ] Add loading states and error handling

**Files to Create**:
```
resources/views/
├── auth/
│   ├── login.blade.php           # Mobile input + OTP
│   └── verify-otp.blade.php      # OTP verification (or same page)

routes/
└── web.php                        # Add auth routes
```

#### 2.3 Testing
- [ ] Create feature tests for auth flow
- [ ] Test OTP validation
- [ ] Test error scenarios
- [ ] Test session management

---

### Phase 3: Dashboard & Layout (Week 4)

**Goal**: Implement main dashboard with navigation

#### 3.1 Dashboard Layout
- [ ] Create main dashboard controller
- [ ] Implement dashboard view (from `dashboard/index.php`)
- [ ] Add sidebar navigation
- [ ] Add header with user info
- [ ] Implement breadcrumb system

**Files to Create**:
```
app/Http/Controllers/Admin/
└── DashboardController.php

resources/views/
└── dashboard/
    └── index.blade.php
```

#### 3.2 Dashboard Widgets
- [ ] Create stat card components
- [ ] Implement quick access modules
- [ ] Add recent activity feed
- [ ] Add notifications panel

#### 3.3 API Integration
- [ ] Create dashboard stats API endpoint
- [ ] Implement data aggregation service
- [ ] Add caching for performance

**New APIs to Create**:
```
GET /api/v1/dashboard/stats
GET /api/v1/dashboard/recent-activities
GET /api/v1/dashboard/notifications
```

---

### Phase 4: Organization Module UI (Week 5-6)

**Goal**: Complete Organization module frontend

#### 4.1 Module Dashboard
- [ ] Create organization module dashboard
- [ ] Implement module-specific sidebar (from `org-sidebar.php`)
- [ ] Add module stats
- [ ] Add quick access cards

**Files to Create**:
```
app/Http/Controllers/Admin/Organization/
└── OrganizationDashboardController.php

resources/views/
└── admin/
    └── organization/
        ├── index.blade.php
        └── components/
            └── sidebar.blade.php
```

#### 4.2 User Management
- [ ] Implement user list page (from `org/users/list.php`)
- [ ] Create user create/edit forms
- [ ] Add user search and filters
- [ ] Implement pagination
- [ ] Add bulk actions

**Reference Prototype**: `docs/prototypes/dashboard/org/users/`

#### 4.3 Department Management
- [ ] Implement department list page
- [ ] Create department create/edit forms
- [ ] Add hierarchical tree view
- [ ] Implement drag-and-drop sorting

**Reference Prototype**: `docs/prototypes/dashboard/org/departments/`

#### 4.4 Job Position Management
- [ ] Implement job position list
- [ ] Create job position forms
- [ ] Add position hierarchy

**Reference Prototype**: `docs/prototypes/dashboard/org/job-positions/`

#### 4.5 Roles & Permissions
- [ ] Implement roles list page
- [ ] Create role management UI
- [ ] Add permission matrix
- [ ] Implement permission assignment

**Reference Prototype**: `docs/prototypes/dashboard/org/roles-permissions/`

---

### Phase 5: Knowledge Base Module (Week 7-9)

**Goal**: Implement new Knowledge Base module (backend + frontend)

#### 5.1 Backend Architecture
- [ ] Create Knowledge module structure
- [ ] Design database schema
- [ ] Create migrations
- [ ] Implement entities (Experience, Template, Category)
- [ ] Create repositories with criteria
- [ ] Implement services
- [ ] Create API endpoints
- [ ] Add transformers

**Module Structure**:
```
app/Core/Knowledge/
├── Entities/
│   ├── Experience.php
│   ├── ExperienceTemplate.php
│   ├── ExperienceCategory.php
│   └── ExperienceComment.php
├── DTOs/
│   ├── CreateExperienceDTO.php
│   └── UpdateExperienceDTO.php
├── Repositories/
│   ├── ExperienceRepository.php
│   ├── ExperienceTemplateRepository.php
│   └── Criteria/
├── Services/
│   ├── ExperienceService.php
│   └── TemplateService.php
├── Http/
│   ├── Controllers/
│   │   ├── ExperienceController.php
│   │   └── TemplateController.php
│   ├── Requests/
│   │   ├── CreateExperienceRequest.php
│   │   └── CreateTemplateRequest.php
│   └── Transformers/
│       ├── ExperienceTransformer.php
│       └── TemplateTransformer.php
├── Database/
│   ├── Migrations/
│   └── Factories/
├── Routes/
│   └── V1/
│       └── routes.php
├── Providers/
│   ├── KnowledgeServiceProvider.php
│   └── KnowledgeEventServiceProvider.php
└── Tests/
```

#### 5.2 Database Design
```sql
-- experiences table
- id
- title
- content (JSON - dynamic fields based on template)
- template_id (FK)
- category_id (FK)
- department_id (FK)
- author_id (FK to users)
- status (draft, published, archived)
- tags (JSON)
- views_count
- likes_count
- created_at
- updated_at

-- experience_templates table
- id
- name
- description
- fields (JSON - form builder schema)
- is_active
- created_at
- updated_at

-- experience_categories table
- id
- name
- slug
- parent_id (self-referencing)
- sort_order
- created_at
- updated_at

-- experience_comments table
- id
- experience_id (FK)
- user_id (FK)
- content
- parent_id (self-referencing for replies)
- created_at
- updated_at
```

#### 5.3 Frontend Implementation
- [ ] Create module dashboard (from `knowledge/index.php`)
- [ ] Implement experience list page
- [ ] Create experience form with dynamic fields
- [ ] Implement template builder
- [ ] Add search and filtering
- [ ] Add tagging system
- [ ] Implement comment system

**Reference Prototypes**:
- `docs/prototypes/dashboard/knowledge/index.php`
- `docs/prototypes/dashboard/knowledge/experiences/`
- `docs/prototypes/dashboard/knowledge/templates/`

---

### Phase 6: Mobile PWA (Week 10-12)

**Goal**: Implement Progressive Web App for employees

#### 6.1 Backend APIs
- [ ] Create mobile-specific API endpoints
- [ ] Implement personalization service
- [ ] Add gamification system
- [ ] Create analytics service
- [ ] Implement notification system

**New APIs**:
```
GET /api/v1/mobile/home
GET /api/v1/mobile/personalized
GET /api/v1/mobile/analytics
GET /api/v1/mobile/profile
POST /api/v1/mobile/onboarding
```

#### 6.2 Gamification System
- [ ] Design points and levels system
- [ ] Create achievements table
- [ ] Implement point calculation service
- [ ] Add leaderboard functionality

**Database Tables**:
```sql
-- user_points table
- user_id
- points
- level
- rank

-- achievements table
- id
- name
- description
- icon
- points_required

-- user_achievements table
- user_id
- achievement_id
- unlocked_at
```

#### 6.3 PWA Implementation
- [ ] Create mobile layout
- [ ] Implement home page (from `app/index.php`)
- [ ] Create personalized feed
- [ ] Implement analytics dashboard
- [ ] Create profile page
- [ ] Add onboarding flow
- [ ] Implement service worker
- [ ] Create manifest.json
- [ ] Add offline support

**Reference Prototypes**: `docs/prototypes/app/`

---

### Phase 7: Documents Module (Week 13-14)

**Goal**: Implement document management system

#### 7.1 Backend Implementation
- [ ] Create Documents module
- [ ] Design file storage architecture
- [ ] Implement upload service
- [ ] Add version control
- [ ] Implement permissions system

#### 7.2 Frontend Implementation
- [ ] Create document browser UI
- [ ] Implement folder navigation
- [ ] Add file upload interface
- [ ] Create favorites system
- [ ] Add recent files view

**Reference Prototypes**: `docs/prototypes/dashboard/documents/`

---

### Phase 8: Testing & Quality Assurance (Week 15)

**Goal**: Comprehensive testing and bug fixes

#### 8.1 Testing
- [ ] Unit tests for all services
- [ ] Integration tests for repositories
- [ ] Feature tests for all APIs
- [ ] E2E tests for critical flows
- [ ] Browser compatibility testing
- [ ] Mobile responsiveness testing
- [ ] Performance testing

#### 8.2 Code Quality
- [ ] Run Laravel Pint for code formatting
- [ ] PHPStan analysis
- [ ] Security audit
- [ ] Accessibility audit (WCAG 2.1)
- [ ] Performance optimization

---

### Phase 9: Documentation & Deployment (Week 16)

**Goal**: Complete documentation and prepare for deployment

#### 9.1 Documentation
- [ ] Update API documentation
- [ ] Create user guides
- [ ] Document deployment process
- [ ] Create admin manual
- [ ] Update CLAUDE.md

#### 9.2 Deployment
- [ ] Set up staging environment
- [ ] Deploy to staging
- [ ] User acceptance testing
- [ ] Fix issues from UAT
- [ ] Deploy to production

---

## 🏗️ Technical Implementation Guidelines

### 1. Component Conversion Strategy

**From PHP Prototype to Blade Component**:

```php
// Prototype: _components/dashboard-header.php
<?php
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<header class="bg-white border-b">
  <h1><?= $pageTitle ?></h1>
</header>

// Blade: resources/views/components/dashboard/header.blade.php
@props(['title' => 'Dashboard'])

<header class="bg-white border-b">
  <h1>{{ $title }}</h1>
</header>

// Usage in Blade
<x-dashboard.header :title="$pageTitle" />
```

### 2. API-First Pattern

**Always use BaseWebController for admin panels**:

```php
// app/Http/Controllers/Admin/Knowledge/ExperienceManagementController.php
class ExperienceManagementController extends BaseWebController
{
    public function index(Request $request): View
    {
        // Fetch data via internal API call
        $response = $this->apiGet('knowledge/experiences', [
            'filter' => $request->get('filter', []),
            'sort' => $request->get('sort', '-created_at'),
            'per_page' => 15,
        ]);

        return view('admin.knowledge.experiences.index', [
            'experiences' => $response['data'],
            'meta' => $response['meta'],
        ]);
    }

    public function create(): View
    {
        // Fetch templates for dropdown
        $templates = $this->apiGet('knowledge/templates');
        
        return view('admin.knowledge.experiences.create', [
            'templates' => $templates['data'],
        ]);
    }
    
    // NO store/update/destroy - handled by Axios from frontend
}
```

### 3. AJAX System Integration

**Use declarative AJAX for forms**:

```blade
{{-- resources/views/admin/knowledge/experiences/create.blade.php --}}
<form
  data-ajax
  action="{{ route('api.knowledge.experiences.store') }}"
  method="POST"
  data-on-success="redirect"
  data-redirect-url="{{ route('admin.knowledge.experiences.index') }}"
  data-on-error="showErrors"
>
  @csrf
  
  <x-forms.input 
    name="title" 
    label="عنوان تجربه" 
    required 
  />
  
  <x-forms.select 
    name="template_id" 
    label="قالب" 
    :options="$templates"
    required 
  />
  
  <x-ui.button type="submit">
    ذخیره تجربه
  </x-ui.button>
</form>
```

### 4. Responsive Design

**Mobile-first approach with Tailwind**:

```blade
{{-- Always start with mobile, then add larger breakpoints --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  {{-- Cards --}}
</div>

{{-- Use container for max-width --}}
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Content --}}
</div>
```

### 5. Performance Optimization

**Eager loading and caching**:

```php
// In Repository
public function getExperiencesWithRelations(array $criteria = []): Collection
{
    return $this->model
        ->with(['author', 'template', 'category', 'department'])
        ->when($criteria['status'] ?? null, fn($q, $status) => $q->where('status', $status))
        ->latest()
        ->get();
}

// In Service - add caching
public function getDashboardStats(): array
{
    return Cache::remember('knowledge.dashboard.stats', 3600, function () {
        return [
            'total_experiences' => $this->experienceRepository->count(),
            'this_month' => $this->experienceRepository->countThisMonth(),
            'templates' => $this->templateRepository->count(),
            'active_departments' => $this->getActiveDepartmentsCount(),
        ];
    });
}
```

---

## 📝 Naming Conventions

### Routes
```php
// Admin panel routes (web)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('knowledge/experiences', [ExperienceManagementController::class, 'index'])
        ->name('knowledge.experiences.index');
});

// API routes
Route::prefix('api/v1')->name('api.')->group(function () {
    Route::apiResource('knowledge/experiences', ExperienceController::class);
});
```

### Views
```
resources/views/
├── admin/                          # Admin panel views
│   ├── knowledge/
│   │   ├── experiences/
│   │   │   ├── index.blade.php    # List page
│   │   │   ├── create.blade.php   # Create form
│   │   │   └── edit.blade.php     # Edit form
│   │   └── templates/
│   │       └── ...
│   └── organization/
│       └── ...
└── mobile/                         # Mobile PWA views
    ├── home.blade.php
    ├── personalized.blade.php
    └── ...
```

### Controllers
```
app/Http/Controllers/
├── Admin/                          # Admin panel controllers (BaseWebController)
│   ├── Knowledge/
│   │   ├── ExperienceManagementController.php
│   │   └── TemplateManagementController.php
│   └── Organization/
│       └── ...
└── API/
    └── V1/
        └── Knowledge/
            ├── ExperienceController.php    # API controller
            └── TemplateController.php
```

---

## 🔒 Security Considerations

1. **CSRF Protection**: All forms must include `@csrf`
2. **API Authentication**: Use API Key authentication for API routes
3. **Authorization**: Implement policies for all resources
4. **Input Validation**: Use Form Requests for all inputs
5. **XSS Prevention**: Always use `{{ }}` in Blade (auto-escaping)
6. **SQL Injection**: Use Eloquent ORM (never raw queries without bindings)
7. **File Upload**: Validate file types and sizes
8. **Rate Limiting**: Apply to authentication and API endpoints

---

## 📊 Success Metrics

### Technical Metrics
- [ ] 100% API coverage for all features
- [ ] >80% test coverage
- [ ] <200ms average page load time
- [ ] <100ms average API response time
- [ ] 0 critical security vulnerabilities
- [ ] Lighthouse score >90 for PWA

### User Experience Metrics
- [ ] Mobile responsive on all devices
- [ ] Accessibility WCAG 2.1 AA compliant
- [ ] Cross-browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Offline functionality for PWA
- [ ] <3 clicks to any feature

---

## 🚨 Risk Management

### Technical Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| **Prototype design doesn't match backend data structure** | High | Medium | Early API contract definition, prototype review |
| **Performance issues with large datasets** | High | Medium | Implement pagination, caching, lazy loading |
| **Browser compatibility issues** | Medium | Low | Cross-browser testing, polyfills |
| **Security vulnerabilities** | High | Low | Security audit, penetration testing |
| **Mobile performance issues** | Medium | Medium | Performance optimization, service worker caching |

### Project Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| **Scope creep** | High | High | Strict phase boundaries, change control |
| **Timeline delays** | Medium | Medium | Buffer time in schedule, prioritization |
| **Resource availability** | Medium | Low | Cross-training, documentation |
| **Requirement changes** | Medium | Medium | Agile approach, regular stakeholder reviews |

---

## 📅 Timeline Summary

| Phase | Duration | Key Deliverables |
|-------|----------|------------------|
| **Phase 1**: Foundation | 2 weeks | Design system, component library |
| **Phase 2**: Authentication | 1 week | Login UI, OTP verification |
| **Phase 3**: Dashboard | 1 week | Main dashboard, navigation |
| **Phase 4**: Organization Module | 2 weeks | Complete org module UI |
| **Phase 5**: Knowledge Base | 3 weeks | New module (backend + frontend) |
| **Phase 6**: Mobile PWA | 3 weeks | Progressive Web App |
| **Phase 7**: Documents Module | 2 weeks | Document management |
| **Phase 8**: Testing & QA | 1 week | Comprehensive testing |
| **Phase 9**: Documentation & Deployment | 1 week | Docs and production deployment |

**Total Duration**: 16 weeks (~4 months)

---

## 🎯 Next Steps

1. **Review this plan** with the product manager and development team
2. **Prioritize phases** based on business needs
3. **Assign resources** to each phase
4. **Set up project tracking** (Jira, Trello, etc.)
5. **Create detailed tickets** for Phase 1
6. **Begin implementation** with Phase 1

---

## 📚 References

- **Prototypes**: `docs/prototypes/`
- **Architecture**: `.claude/architecture.md`
- **AJAX System**: `.claude/ajax-system-overview.md`
- **BaseWebController**: `.claude/presentation/base-web-controller.md`
- **Testing Guide**: `.claude/testing/guide.md`
- **API Design**: `.claude/api/design.md`

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-27  
**Status**: Draft - Awaiting Review
