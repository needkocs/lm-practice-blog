export interface User {
    id: number;
    name: string;
    email?: string;
    created_at?: string;
    followers_count?: number;
    following_count?: number;
}

export interface Tag {
    id: number;
    name: string;
    slug: string;
}

export interface Post {
    id: number;
    title: string;
    slug: string;
    content: string | null;
    excerpt: string;
    visibility: 'public' | 'request_only';
    visibility_label: string;
    published_at: string | null;
    created_at: string | null;
    author: User;
    tags: Tag[];
    can: {
        view_full: boolean;
        update: boolean;
        delete: boolean;
    };
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginator<T> {
    data: T[];
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
}

export interface SelectOption {
    value: string;
    label: string;
}
