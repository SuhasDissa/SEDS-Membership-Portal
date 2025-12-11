# MEDIUM PRIORITY Features Implementation Plan

## Overview
This document outlines the MEDIUM PRIORITY features to be implemented in the SEDS Membership Portal admin dashboard.

---

## ✅ COMPLETED: Admin Navigation Enhancement
- [x] Added "Contributions" link to admin menu
- [x] Added "Posts" link to admin menu
- [x] Admin menu now shows: Dashboard, Members, Contributions, Posts

---

## 📋 FEATURES TO IMPLEMENT

### 1. 📊 Activity Log - Track Admin Actions

**Purpose**: Keep a comprehensive audit trail of all admin activities for accountability and security

**Features**:
- Log all admin actions (approve/reject contributions, create/edit/delete posts, member approvals, etc.)
- Store: action type, admin user, target (what was modified), timestamp, IP address
- Filterable by: date range, admin user, action type
- Searchable log entries
- Export logs to CSV

**Database Table needed**:
```sql
activity_logs:
  - id
  - admin_user_id (foreign key to users)
  - action (enum: approved_contribution, rejected_contribution, created_post, etc.)
  - model_type (morphs: Contribution, Post, User, etc.)
  - model_id
  - old_values (json)
  - new_values (json)
  - ip_address
  - user_agent
  - timestamps
```

**Files to create**:
- `app/Models/ActivityLog.php`
- `app/Livewire/Admin/ActivityLogs.php`
- `resources/views/livewire/admin/activity-logs.blade.php`
- `database/migrations/xxxx_create_activity_logs_table.php`

**Implementation Steps**:
1. Create migration and model
2. Create trait `LogsActivity` to auto-log actions
3. Apply trait to admin actions in Posts and Contributions components
4. Create admin page to view logs
5. Add to admin navigation

---

### 2. 🔍 Advanced Filters & Search

**Purpose**: Better data filtering and search capabilities across all admin pages

**Features**:
- **Contributions Page**:
  - Filter by: status, date range, member, contribution type
  - Search by: title, description, member name
  - Sort by: date, status, member
  
- **Posts Page** (partially done):
  - Filter by: status, category, featured, date range
  - Search by: title, content, author
  
- **Members Page**:
  - Filter by: approval status, admin status, faculty, registration date
  - Search by: name, email, university ID

**Files to modify**:
- `app/Livewire/Admin/Contributions.php` - Add advanced filters
- `app/Livewire/Admin/Posts.php` - Enhance existing filters
- `app/Livewire/Admin/Members/Index.php` - Add advanced filters
- Update blade views with filter UI

**Implementation Steps**:
1. Add date range picker component
2. Add filter properties to each Livewire component
3. Update query builders with filters
4. Create reusable filter UI components
5. Add "Clear Filters" button

---

### 3. ⚡ Bulk Actions

**Purpose**: Improve admin efficiency by allowing bulk operations

**Features**:
- **Bulk Approve** multiple contributions at oncebulk Reject** multiple contributions with same reason
- **Bulk Delete** posts/contributions
- **Bulk Feature/Unfeature** posts
- **Bulk Export** selected items to CSV

**UI Design**:
- Checkbox column in tables
- "Select All" checkbox in header
- Bulk action dropdown appears when items selected
- Confirmation modal for destructive actions

**Files to modify**:
- `app/Livewire/Admin/Contributions.php`
- `app/Livewire/Admin/Posts.php`
- `resources/views/livewire/admin/contributions.blade.php`
- `resources/views/livewire/admin/posts.blade.php`

**Implementation Steps**:
1. Add `$selectedIds = []` property to components
2. Add checkbox column to tables
3. Add bulk action methods (bulkApprove, bulkReject, etc.)
4. Create bulk action UI toolbar
5. Add confirmation modals

---

### 4. 🏷️ Post Categories & Tags Enhancement

**Purpose**: Better post organization and discoverability

**Current State**: Basic categories exist (announcement, event, news, achievement, resource, general)

**Enhancements Needed**:
- **Custom Tags**: Add tagging system for posts
- **Category Management**: Admin can add/edit/delete custom categories
- **Tag Cloud**: Display popular tags on member dashboard
- **Filter by Tags**: Members can filter feed by tags

**Database Changes**:
```sql
tags:
  - id
  - name
  - slug
  - usage_count
  - timestamps

post_tag (pivot):
  - post_id
  - tag_id

categories (new table):
  - id
  - name
  - slug
  - icon emoji
  - color
  - timestamps
```

**Files to create**:
- `app/Models/Tag.php`
- `app/Models/Category.php`
- `app/Livewire/Admin/Categories.php`
- `database/migrations/xxxx_create_tags_table.php`
- `database/migrations/xxxx_create_categories_table.php`

**Implementation Steps**:
1. Create tags and categories tables
2. Update Post model with relationships
3. Add tag input to post creation/editing
4. Create category management page
5. Update member feed with tag filtering

---

### 5. 🔔 Notification System

**Purpose**: Auto-notify members of important events

**Notifications to Implement**:
- ✉️ **Contribution Approved**: Email + in-app notification
- ❌ **Contribution Rejected**: Email with rejection reason
- 🎉 **Account Approved**: Welcome email when admin approves account
- 📢 **New Announcement**: Notify all members when featured announcement posted
- ⭐ **Achievement Recognition**: Notify when reaching milestones

**Database Table**:
```sql
notifications:
  - id
  - user_id
  - type (enum)
  - title
  - message
  - data (json)
  - read_at
  - timestamps
```

**Files to create**:
- `app/Models/Notification.php`
- `app/Notifications/ContributionApproved.php` (Mail + Database)
- `app/Notifications/ContributionRejected.php`
- `app/Notifications/AccountApproved.php`
- `app/Livewire/Notifications/Index.php`
- `resources/views/livewire/notifications/index.blade.php`
- `resources/views/emails/contribution-approved.blade.php`
- `resources/views/emails/contribution-rejected.blade.php`

**Implementation Steps**:
1. Set up Laravel notifications
2. Create notification classes
3. Create email templates
4. Add notification bell icon to navbar
5. Create notifications page
6. Send notifications on events (approve/reject)
7. Add email queue configuration

---

### 6. 📈 Analytics & Data Science Features

**Purpose**: Provide insights into member activities and contributions

**Dashboard Widgets**:

#### A. Time-Based Analytics
- **Monthly Contributions**: Line chart showing contributions per month
- **Yearly Comparison**: Compare current year vs previous year
- **Weekly Activity**: Heatmap of activity by day of week

#### B. Member Analytics
- **Top Contributors**: 
  - Monthly top 10
  - Yearly top 10
  - All-time leaderboard
- **Contribution Categories**: Pie chart of contribution types
- **Member Growth**: Chart showing member registrations over time

#### C. Custom Date Range Reports
- Select custom date range
- Filter by member, contribution type, status
- Export report to PDF/CSV
- Show stats: total contributions, approval rate, avg per member

#### D. Contribution Insights
- **Approval Rate**: % of contributions approved vs rejected
- **Response Time**: Avg time from submission to approval/rejection
- **Most Active Periods**: Times when most contributions submitted
- **Member Engagement**: % of approved members who contribute

**Files to create**:
- `app/Livewire/Admin/Analytics/Overview.php`
- `app/Livewire/Admin/Analytics/Contributors.php`
- `app/Livewire/Admin/Analytics/Reports.php`
- `resources/views/livewire/admin/analytics/overview.blade.php`
- `resources/views/livewire/admin/analytics/contributors.blade.php`
- `resources/views/livewire/admin/analytics/reports.blade.php`
- `app/Services/AnalyticsService.php` (for complex calculations)

**Charts Library**: Use Laravel Charts or Chart.js

**Implementation Steps**:
1. Install charting library (e.g., `composer require consoletvs/charts`)
2. Create analytics service for data aggregation
3. Create analytics dashboard page
4. Build individual widgets/charts
5. Add date range picker
6. Add export functionality
7. Cache expensive queries

---

## 🗂️ Suggested Implementation Order

### Phase 1: Core Improvements (Week 1)
1. ✅ Admin Navigation (DONE)
2. Advanced Filters & Search
3. Bulk Actions

### Phase 2: Organization (Week 2)
4. Activity Log
5. Post Categories & Tags Enhancement

### Phase 3: Engagement (Week 3)
6. Notification System

### Phase 4: Insights (Week 4)
7. Analytics & Data Science Features

---

## 📊 Technical Requirements

### Required  Packages:
```bash
# For charts
composer require consoletvs/charts:7.*

# For PDF export
composer require barryvdh/laravel-dompdf

# For Excel/CSV export (optional)
composer require maatwebsite/excel

# For better date handling
npm install flatpickr --save
```

### Configuration Needs:
- **Email**: Configure SMTP in `.env` for notifications
- **Queue**: Set up queue worker for background jobs
- **Cache**: Use Redis/Memcached for analytics caching

### Database Migrations Needed:
1. `create_activity_logs_table`
2. `create_tags_table`
3. `create_categories_table`
4. `create_post_tag_table`
5. `create_notifications_table`

---

## 🎯 Expected Impact

### For Admins:
- ⏱️ 50% faster approval workflows with bulk actions
- 📊 Better data-driven decisions with analytics
- 🔍 Easier to find specific contributions/posts
- 📝 Complete audit trail for accountability

### For Members:
- 🔔 Instant notification of contribution status
- 🏆 Motivation through leaderboards
- 🎯 Better organized content with tags
- 📢 Timely awareness of announcements

### For the Portal:
- 📈 Increased engagement through notifications
- 🎨 Better content organization
- 💡 Insights for improvement
- 🔒 Enhanced security with activity logs

---

## 🚀 Ready to Start!

All HIGH PRIORITY features are complete! ✅
The foundation is ready for these MEDIUM PRIORITY enhancements.

Would you like me to start implementing any specific feature from this list?
