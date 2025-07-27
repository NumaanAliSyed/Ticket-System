<script setup>
import { ref, computed  } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage  } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);


const page = usePage();

const notifications = computed(() => page.props.auth.user.notifications ?? []);
const unreadCount = computed(() => notifications.value.filter(n => !n.read_at).length);

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user.user_role === 'user'"
                                    :href="route('tickets.index')" :active="route().current('tickets.index')">
                                    Tickets
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user.user_role === 'user'"
                                    :href="route('tickets.create')" :active="route().current('tickets.create')">
                                    Create Ticket
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user.user_role === 'admin'"
                                    :href="route('admin.tickets.index')"
                                    :active="route().current('admin.tickets.index')">
                                    Admin Tickets
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">


                            <!-- Notifications -->
                            <div class="relative me-3">
                                <Dropdown align="right" >
                                    <template #trigger>
                                        <button type="button"
                                            class="relative inline-flex items-center justify-center text-gray-600 hover:text-gray-800 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 
                          6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C8.67 
                          6.165 8 7.388 8 8.75V14.16c0 .538-.214 1.055-.595 
                          1.436L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            <span v-if="unreadCount > 0"
                                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                                {{ unreadCount }}
                                            </span>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="max-h-64 overflow-y-auto">
                                            <template v-if="notifications.length">
                                                <div v-for="notification in notifications" :key="notification.id"
                                                    class="px-4 py-2 text-sm text-gray-700 border-b">
                                                    <div>{{ notification.data.message }}</div>
                                                    <div class="text-xs text-muted">Subject: {{
                                                        notification.data.subject }}</div>
                                                    <Link :href="route('tickets.show', notification.data.ticket_id)"
                                                        class="text-blue-600">
                                                    View
                                                    </Link>
                                                </div>
                                            </template>
                                            <div v-else class="px-4 py-2 text-sm text-gray-500">
                                                No notifications.
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>


                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user.user_role === 'user'"
                            :href="route('tickets.index')" :active="route().current('tickets.index')">
                            Tickets
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user.user_role === 'user'"
                            :href="route('tickets.create')" :active="route().current('tickets.create')">
                            Create Ticket
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user.user_role === 'admin'"
                            :href="route('admin.tickets.index')" :active="route().current('admin.tickets.index')">
                            Admin Tickets
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>