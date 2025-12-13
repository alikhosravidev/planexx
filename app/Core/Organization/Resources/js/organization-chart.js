/**
 * Organization Chart - Balkan OrgChart Implementation
 *
 * This module handles the organizational chart visualization using Balkan OrgChart library
 */

// Import OrgChart library
import OrgChart from '@balkangraph/orgchart.js';

/**
 * Initialize and configure the organizational chart
 * @param {Array} departments - The departments data from API
 */
function initOrganizationChart(departments) {
  OrgChart.templates.department = Object.assign({}, OrgChart.templates.isla);
  OrgChart.templates.department.size = [220, 120];
  OrgChart.templates.department.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#1e3a8a" stroke-width="1" stroke="#1e40af" rx="5" ry="5"></rect>';
  OrgChart.templates.department.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
  OrgChart.templates.department.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
  OrgChart.templates.department.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#1e3a8a" stroke-width="4" fill="#1e3a8a" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

  OrgChart.templates.subdepartment = Object.assign({}, OrgChart.templates.isla);
  OrgChart.templates.subdepartment.size = [220, 120];
  OrgChart.templates.subdepartment.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#0ea5e9" stroke-width="1" stroke="#0284c7" rx="5" ry="5"></rect>';
  OrgChart.templates.subdepartment.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
  OrgChart.templates.subdepartment.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
  OrgChart.templates.subdepartment.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#0ea5e9" stroke-width="4" fill="#0ea5e9" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

  OrgChart.templates.team = Object.assign({}, OrgChart.templates.isla);
  OrgChart.templates.team.size = [220, 120];
  OrgChart.templates.team.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#059669" stroke-width="1" stroke="#047857" rx="5" ry="5"></rect>';
  OrgChart.templates.team.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
  OrgChart.templates.team.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
  OrgChart.templates.team.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#059669" stroke-width="4" fill="#059669" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

  OrgChart.templates.employee = Object.assign({}, OrgChart.templates.isla);
  OrgChart.templates.employee.size = [220, 120];
  OrgChart.templates.employee.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#7c3aed" stroke-width="1" stroke="#6d28d9" rx="5" ry="5"></rect>';
  OrgChart.templates.employee.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
  OrgChart.templates.employee.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
  OrgChart.templates.employee.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#7c3aed" stroke-width="4" fill="#7c3aed" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

  const chart = new OrgChart(document.getElementById("tree"), {
    mouseScrool: OrgChart.none,
    layout: OrgChart.normal,
    enableSearch: false,
    linkBinding: {
      link_field_0: "linkStyle"
    },
    orderBy: "order",
    collapse: {
      level: 2
    },
    nodeBinding: {
      img_0: "img",
      field_0: "name",
      field_1: "title"
    },
    siblingSeparation: 30,
    subtreeSeparation: 40,
    tags: {
      "department": {
        template: "department"
      },
      "subdepartment": {
        template: "subdepartment"
      },
      "team": {
        template: "team"
      },
      "employee": {
        template: "employee"
      }
    }
  });

  const chartData = transformDepartments(departments);

  if (chartData.length === 0) {
    chartData.push({
      id: "1",
      name: "سازمان شما",
      title: "هنوز دپارتمانی تعریف نشده است",
      tags: ["department"],
      img: "https://ui-avatars.com/api/?name=Organization&background=1e3a8a&color=fff&size=200"
    });
  }

  chart.load(chartData);

  setupControlButtons(chart);
  setupSidebar();

  chart.on('click', function(sender, args) {
    if (args.node) {
      const nodeId = args.node.id;
      const nodeData = sender.get(nodeId);
      showNodeInfo(nodeData);
    }
  });

  return chart;
}

function countTotalUsers(dept) {
  let count = 0;

  if (dept.users && Array.isArray(dept.users)) {
    count += dept.users.length;
  }

  if (dept.childrenWithUsers && Array.isArray(dept.childrenWithUsers)) {
    dept.childrenWithUsers.forEach(child => {
      count += countTotalUsers(child);
    });
  }

  return count;
}

function transformDepartments(depts, parentId = null, level = 0) {
  const nodes = [];

  depts.forEach((dept, index) => {
    const defaultImage = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(dept.name) + '&background=random&size=200';

    const thumbnailImage = dept.thumbnail?.file_url || defaultImage;

    let tag = 'department';
    if (level === 1) {
      tag = 'subdepartment';
    } else if (level === 2) {
      tag = 'team';
    } else if (level >= 3) {
      tag = 'employee';
    }

    const employeeCount = dept.users.length || 0;
    const titleText = dept.type?.label || 'دپارتمان';
    const titleWithCount = employeeCount > 0 ? `${titleText} (${employeeCount} نفر)` : titleText;

    const directUsersCount = dept.users ? dept.users.length : 0;
    const totalUsersCount = countTotalUsers(dept);

    nodes.push({
      id: dept.id.toString(),
      pid: parentId,
      name: dept.name,
      title: titleWithCount,
      tags: [tag],
      img: thumbnailImage,
      order: index,
      // اطلاعات اضافی برای سایدبار
      code: dept.code || '-',
      type: dept.type?.label || 'دپارتمان',
      manager: dept.manager?.full_name || '-',
      employeesCount: employeeCount,
      status: dept.status || 'active'
    });

    if (dept.users && dept.users.length > 0) {
      console.log(`  👥 در حال اضافه کردن ${dept.users.length} کاربر برای ${dept.name}`);
      dept.users.forEach((user, userIndex) => {
        const userFullName = user.full_name || 'کاربر';
        const userImg = user.avatar?.file_url || user.thumbnail?.file_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userFullName) + '&background=7c3aed&color=fff&size=200';
        const userTitle = user.user_type?.label || 'کارمند';

        nodes.push({
          id: `user_${dept.id}_${user.id}`,
          pid: dept.id.toString(),
          name: userFullName,
          title: userTitle,
          tags: ['employee'],
          img: userImg,
          order: level * 100 + userIndex,
          phone: user.phone || user.mobile || '-',
          email: user.email || '-',
          status: user.status || 'active'
        });
      });
    }

    if (dept.childrenWithUsers && dept.childrenWithUsers.length > 0) {
      const childrenNodes = transformDepartments(dept.childrenWithUsers, dept.id.toString(), level + 1);
      nodes.push(...childrenNodes);
    }
  });

  return nodes;
}

function setupControlButtons(chart) {
  const pdfBtn = document.getElementById('export-pdf');
  if (pdfBtn) {
    pdfBtn.addEventListener('click', () => {
      chart.exportToPDF({ filename: 'organizational-chart.pdf' });
    });
  }

  const svgBtn = document.getElementById('export-svg');
  if (svgBtn) {
    svgBtn.addEventListener('click', () => {
      chart.exportToSVG({ filename: 'organizational-chart.svg' });
    });
  }

  const zoomInBtn = document.getElementById('zoom-in');
  if (zoomInBtn) {
    zoomInBtn.addEventListener('click', () => {
      chart.zoom(true, [0.5, 0.5], true);
    });
  }

  const zoomOutBtn = document.getElementById('zoom-out');
  if (zoomOutBtn) {
    zoomOutBtn.addEventListener('click', () => {
      chart.zoom(false, [0.5, 0.5], true);
    });
  }

  const fitBtn = document.getElementById('fit-screen');
  if (fitBtn) {
    fitBtn.addEventListener('click', () => {
      chart.fit();
    });
  }
}

function setupSidebar() {
  const sidebar = document.getElementById('info-sidebar');
  const closeBtn = document.getElementById('close-sidebar');

  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      sidebar.style.right = '-400px';
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('mouseenter', () => {
      closeBtn.style.background = '#e2e8f0';
      closeBtn.style.color = '#0f172a';
    });
    closeBtn.addEventListener('mouseleave', () => {
      closeBtn.style.background = '#f8fafc';
      closeBtn.style.color = '#64748b';
    });
  }
}

function showNodeInfo(nodeData) {
  const sidebar = document.getElementById('info-sidebar');
  const sidebarTitle = document.getElementById('sidebar-title');
  const sidebarContent = document.getElementById('sidebar-content');

  sidebar.style.right = '0';

  const isUser = nodeData.id.toString().startsWith('user_');

  if (isUser) {
    sidebarTitle.textContent = 'اطلاعات کارمند';
    sidebarContent.innerHTML = `
      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="display: flex; justify-content: center;">
          <img src="${nodeData.img || ''}" alt="${nodeData.name}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);" />
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">نام کارمند</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.name || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">نقش کاربری</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.title || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">شماره تماس</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;" dir="ltr">${nodeData.phone || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">ایمیل</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500; word-break: break-all;" dir="ltr">${nodeData.email || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">وضعیت</div>
            <div style="display: inline-block; padding: 0.375rem 0.75rem; border-radius: 0.5rem; background: ${nodeData.status === 'active' ? '#dcfce7' : '#fee2e2'}; color: ${nodeData.status === 'active' ? '#166534' : '#991b1b'}; font-size: 0.875rem; font-weight: 500;">
              ${nodeData.status === 'active' ? 'فعال' : 'غیرفعال'}
            </div>
          </div>
        </div>
      </div>
    `;
  } else {
    sidebarTitle.textContent = 'اطلاعات دپارتمان';
    sidebarContent.innerHTML = `
      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="display: flex; justify-content: center;">
          <img src="${nodeData.img || ''}" alt="${nodeData.name}" style="width: 120px; height: 120px; border-radius: 0.75rem; object-fit: cover; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);" />
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">نام دپارتمان</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.name || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">کد</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.code || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">نوع</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.type || nodeData.title || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">مدیر</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.manager || '-'}</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">تعداد کارکنان</div>
            <div style="color: #0f172a; font-size: 1rem; font-weight: 500;">${nodeData.employeesCount || '0'} نفر</div>
          </div>

          <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem;">
            <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">وضعیت</div>
            <div style="display: inline-block; padding: 0.375rem 0.75rem; border-radius: 0.5rem; background: ${nodeData.status === 'active' ? '#dcfce7' : '#fee2e2'}; color: ${nodeData.status === 'active' ? '#166534' : '#991b1b'}; font-size: 0.875rem; font-weight: 500;">
              ${nodeData.status === 'active' ? 'فعال' : 'غیرفعال'}
            </div>
          </div>
        </div>
      </div>
    `;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  if (window.departmentsData && document.getElementById('tree')) {
    initOrganizationChart(window.departmentsData);
  }
});
