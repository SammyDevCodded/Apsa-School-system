import re

try:
    with open('resources/views/settings/index.php', 'r', encoding='utf-8') as f:
        content = f.read()

    # Make backup
    with open('resources/views/settings/index.php.bak', 'w', encoding='utf-8') as f:
        f.write(content)

    sidebar_html = """
        <div class="flex flex-col lg:flex-row gap-6 mb-6">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <nav class="space-y-1 bg-white shadow sm:rounded-lg p-3" aria-label="Sidebar">
                    <button type="button" data-target="panel-school" class="settings-tab bg-indigo-50 text-indigo-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-indigo-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="truncate">School Information</span>
                    </button>
                    
                    <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                    <button type="button" data-target="panel-datetime" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="truncate">Date & Time Settings</span>
                    </button>
                    
                    <button type="button" data-target="panel-watermark" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="truncate">Watermark Settings</span>
                    </button>
                    <?php endif; ?>
                    
                    <button type="button" data-target="panel-report" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="truncate">Report Card Settings</span>
                    </button>
                    
                    <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                    <button type="button" data-target="panel-academic" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="truncate">Academic Settings</span>
                    </button>
                    
                    <button type="button" data-target="panel-autogen" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        <span class="truncate">Auto-Generate Settings</span>
                    </button>
                    
                    <button type="button" data-target="panel-grading" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span class="truncate">Grading System</span>
                    </button>
                    
                    <button type="button" data-target="panel-import" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        <span class="truncate">Bulk Data Import</span>
                    </button>
                    <?php endif; ?>
                    
                    <?php if (isset($isTrueSuperAdmin) && $isTrueSuperAdmin): ?>
                    <button type="button" data-target="panel-wipe" class="settings-tab text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-red-500 group-hover:text-red-600 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        <span class="truncate">System Wipe</span>
                    </button>
                    <?php endif; ?>
                    
                    <button type="button" data-target="panel-sysinfo" class="settings-tab text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full text-left transition-colors">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="truncate">System Information</span>
                    </button>
                </nav>
            </div>
            
            <!-- Content Area -->
            <div class="w-full lg:w-3/4">
"""

    content = content.replace("<!-- Settings Form -->", f"<!-- Settings Form -->\n{sidebar_html}")

    # Process all details blocks
    # regex for <details> ... <summary> ... <div> ... </div> <div class="ml-4...svg...</div> </summary>
    def details_replacer(match):
        header_content = match.group(1)
        if "School Information" in header_content:
            panel_id = "panel-school"
            initial_class = "block"
        elif "Date & Time" in header_content:
            panel_id = "panel-datetime"
            initial_class = "hidden"
        elif "Watermark" in header_content:
            panel_id = "panel-watermark"
            initial_class = "hidden"
        elif "Report Card" in header_content:
            panel_id = "panel-report"
            initial_class = "hidden"
        elif "Academic Settings" in header_content:
            panel_id = "panel-academic"
            initial_class = "hidden"
        elif "Auto-Generate" in header_content:
            panel_id = "panel-autogen"
            initial_class = "hidden"
        elif "Grading System" in header_content:
            panel_id = "panel-grading"
            initial_class = "hidden"
        else:
            panel_id = "panel-unknown"
            initial_class = "hidden"
            
        return f'<div id="{panel_id}" class="settings-panel {initial_class} bg-white shadow overflow-hidden sm:rounded-lg mb-6">\n<div class="px-4 py-5 sm:px-6 border-b border-gray-200">\n{header_content}\n</div>'

    # Replace <details...> <summary> <div>...</div> <div ml-4>svg</div> </summary>
    pattern = re.compile(
        r'<details[^>]*>\s*<summary[^>]*>\s*<div>(.*?)</div>\s*<div class="ml-4[^>]*>.*?</div>\s*</summary>', 
        re.DOTALL | re.IGNORECASE
    )
    content = pattern.sub(details_replacer, content)

    # Note: Bulk Data Import, System Wipe, and System Information are already <div>s, not <details>
    def div_replacer(match):
        title = match.group(0)
        if "Bulk Data Import" in title:
            panel_id = "panel-import"
        elif "System Wipe" in title:
            panel_id = "panel-wipe"
        elif "System Information" in title:
            panel_id = "panel-sysinfo"
        else:
            return match.group(0) # Do not modify
        
        # We need to replace class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg" (or similar)
        # with id="panel-x" class="settings-panel hidden bg-white shadow overflow-hidden sm:rounded-lg"
        # Since we just captured the text, let's just do a specific string replace
        return match.group(0)

    # Let's string-replace the non-details sections specifically
    content = content.replace(
        '<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">\n            <div class="px-4 py-5 sm:px-6">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Data Import</h3>',
        '<div id="panel-import" class="settings-panel hidden bg-white shadow overflow-hidden sm:rounded-lg mb-6">\n            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Data Import</h3>'
    )
    
    content = content.replace(
        '<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">\n            <div class="px-4 py-5 sm:px-6">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">System Wipe</h3>',
        '<div id="panel-wipe" class="settings-panel hidden bg-white shadow overflow-hidden sm:rounded-lg mb-6">\n            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">System Wipe</h3>'
    )
    
    content = content.replace(
        '<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">\n            <div class="px-4 py-5 sm:px-6">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">System Information</h3>',
        '<div id="panel-sysinfo" class="settings-panel hidden bg-white shadow overflow-hidden sm:rounded-lg mb-6">\n            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">\n                <h3 class="text-lg leading-6 font-medium text-gray-900">System Information</h3>'
    )

    # Replace all closing </details> with </div>
    content = content.replace('</details>', '</div>')

    # Close the two flex columns right before <script>
    content = content.replace('</div>\n</div>\n\n<script>', '</div>\n</div>\n            </div>\n        </div>\n    </div>\n</div>\n\n<script>')

    # Add JS logic to script
    js_logic = """
    // Tab switching logic
    const tabs = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');
    
    // Check local storage for active tab
    const activeTabId = localStorage.getItem('settingsActiveTab') || 'panel-school';
    
    function activateTab(tabId) {
        // Hide all panels
        panels.forEach(panel => {
            panel.classList.remove('block');
            panel.classList.add('hidden');
        });
        
        // Reset all tabs
        tabs.forEach(tab => {
            if (tab.getAttribute('data-target') === 'panel-wipe') {
                tab.classList.remove('bg-red-50', 'text-red-700');
                tab.classList.add('text-red-600', 'hover:bg-red-50');
            } else {
                tab.classList.remove('bg-indigo-50', 'text-indigo-700');
                tab.classList.add('text-gray-700', 'hover:bg-gray-50', 'hover:text-gray-900');
            }
        });
        
        // Show selected panel
        const targetPanel = document.getElementById(tabId);
        if (targetPanel) {
            targetPanel.classList.remove('hidden');
            targetPanel.classList.add('block');
        } else if (panels.length > 0) {
            panels[0].classList.remove('hidden');
            panels[0].classList.add('block');
            tabId = panels[0].id;
        }
        
        // Highlight active tab
        const activeTab = document.querySelector(`.settings-tab[data-target="${tabId}"]`);
        if (activeTab) {
            if (tabId === 'panel-wipe') {
                activeTab.classList.remove('text-red-600', 'hover:bg-red-50');
                activeTab.classList.add('bg-red-50', 'text-red-700');
            } else {
                activeTab.classList.remove('text-gray-700', 'hover:bg-gray-50', 'hover:text-gray-900');
                activeTab.classList.add('bg-indigo-50', 'text-indigo-700');
            }
        }
        
        // Save to local storage
        localStorage.setItem('settingsActiveTab', tabId);
    }
    
    // Initialize active tab
    activateTab(activeTabId);
    
    // Add click listeners
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            activateTab(target);
        });
    });
    
"""
    content = content.replace("document.addEventListener('DOMContentLoaded', function() {\n", "document.addEventListener('DOMContentLoaded', function() {\n" + js_logic)

    # Fix the duplicate form closing in School Information that was a bug in original PHP
    bad_school_form = """            </div>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6" enctype="multipart/form-data">
                    <!-- ... existing form fields ... -->
                </form>
            </div>"""
    content = content.replace(bad_school_form, "            </div>")
    
    with open('resources/views/settings/index.php.new', 'w', encoding='utf-8') as f:
        f.write(content)
        
    print("Successfully processed index.php")
except Exception as e:
    print(f"Error: {str(e)}")
