const API_BASE = ''; // Empty means relative to current domain (e.g., /api/articles)
let quillEditor = null;

// Handle Standard Login
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const u = document.getElementById('username').value;
    const p = document.getElementById('password').value;
    const errDiv = document.getElementById('errorMessage');
    
    try {
      const res = await fetch(`${API_BASE}/api/auth/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: u, password: p })
      });
      
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Login failed');
      
      localStorage.setItem('tpb_token', data.token);
      localStorage.setItem('tpb_user', JSON.stringify(data.user));
      window.location.href = 'dashboard.html';
    } catch (err) {
      errDiv.textContent = err.message;
      errDiv.classList.remove('hidden');
    }
  });
}

// Handle Google Login Callback
async function handleGoogleLogin(response) {
  const errDiv = document.getElementById('errorMessage');
  try {
    const res = await fetch(`${API_BASE}/api/auth/google`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ token: response.credential })
    });
    
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Google Login failed');
    
    localStorage.setItem('tpb_token', data.token);
    localStorage.setItem('tpb_user', JSON.stringify(data.user));
    window.location.href = 'dashboard.html';
  } catch (err) {
    if(errDiv) {
      errDiv.textContent = err.message;
      errDiv.classList.remove('hidden');
    } else {
      alert(err.message);
    }
  }
}

// Auth Check Helper
function checkAuth() {
  const token = localStorage.getItem('tpb_token');
  if (!token) {
    window.location.href = 'index.html';
  }
}

function logout() {
  localStorage.removeItem('tpb_token');
  localStorage.removeItem('tpb_user');
  window.location.href = 'index.html';
}

// Load Dashboard Articles
async function loadDashboardArticles() {
  const token = localStorage.getItem('tpb_token');
  const user = JSON.parse(localStorage.getItem('tpb_user') || '{}');
  const list = document.getElementById('articleList');
  
  if (!list) return;

  try {
    // If admin, they can see pending. If writer, they can just see all articles for now.
    // We'll fetch all articles and filter by author if needed.
    const res = await fetch(`${API_BASE}/api/articles`);
    const articles = await res.json();
    
    list.innerHTML = '';
    
    if (articles.length === 0) {
      list.innerHTML = '<li class="p-6 text-center text-gray-500">No articles found. Write your first one!</li>';
      return;
    }

    articles.forEach(article => {
      // In a real app we'd filter by author id, but for this demo we'll show them.
      const statusColor = article.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
      const li = document.createElement('li');
      li.className = 'hover:bg-gray-50 transition';
      li.innerHTML = `
        <div class="px-6 py-4 flex items-center justify-between">
          <div class="flex flex-col">
            <span class="text-lg font-bold text-gray-900">${article.title}</span>
            <span class="text-sm text-gray-500 mt-1">${article.category} &bull; ${new Date(article.createdAt).toLocaleDateString()}</span>
          </div>
          <div>
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColor}">
              ${article.status.charAt(0).toUpperCase() + article.status.slice(1)}
            </span>
          </div>
        </div>
      `;
      list.appendChild(li);
    });

  } catch (err) {
    list.innerHTML = `<li class="p-6 text-center text-red-500">Error loading articles: ${err.message}</li>`;
  }
}

// Initialize Quill Editor
function initEditor() {
  const container = document.getElementById('editor-container');
  if (!container) return;

  quillEditor = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Write your story here...',
    modules: {
      toolbar: [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'image'],
        ['clean']
      ]
    }
  });

  const form = document.getElementById('articleForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const errDiv = document.getElementById('errorMessage');
    
    btn.disabled = true;
    btn.textContent = 'Submitting...';
    errDiv.classList.add('hidden');

    const bodyHtml = quillEditor.root.innerHTML;
    if (quillEditor.getText().trim().length === 0) {
      errDiv.textContent = 'Article body cannot be empty.';
      errDiv.classList.remove('hidden');
      btn.disabled = false;
      btn.textContent = 'Submit for Publishing';
      return;
    }

    const payload = {
      title: document.getElementById('title').value,
      category: document.getElementById('category').value,
      imageUrl: document.getElementById('imageUrl').value,
      body: bodyHtml
    };

    try {
      const res = await fetch(`${API_BASE}/api/articles`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('tpb_token')}`
        },
        body: JSON.stringify(payload)
      });
      
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Failed to submit article');
      
      window.location.href = 'dashboard.html';
    } catch (err) {
      errDiv.textContent = err.message;
      errDiv.classList.remove('hidden');
      btn.disabled = false;
      btn.textContent = 'Submit for Publishing';
    }
  });
}
