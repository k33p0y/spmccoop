
const routes = [
  {
    path: '/',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') }
    ]
  },
  {
    path: '/election',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/election/Index.vue') }
    ]
  },
  {
    path: '/election-detail',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/election-detail/Index.vue') }
    ]
  },
  {
    path: '/position',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/position/Index.vue') }
    ]
  },
  {
    path: '/profile',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/profile/Index.vue') }
    ]
  },
  {
    path: '/member',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/member/Index.vue') }
    ]
  },
  {
    path: '/voter',
    component: () => import('layouts/default/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/voter-client/IndexPage.vue') },
      { path: 'result-blind', component: () => import('pages/voter-client/IndexResult.vue') }
    ]
  },
  {
    path: '/voters',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/voter/Index.vue') }
    ]
  },
  {
    path: '/raffle-winners',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/raffle/Index.vue') }
    ]
  },
  {
    path: '/raffle',
    component: () => import('layouts/master/Layout.vue'),
    children: [
      { path: '', component: () => import('pages/raffle/Raffle.vue') }
    ]
  },
  {
    path: '/oauth/signin',
    component: () => import('layouts/nominal/Layout.vue'),
    children: [
        { path: '', component: () => import('pages/oauth/SignIn.vue') }
    ]
},

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
