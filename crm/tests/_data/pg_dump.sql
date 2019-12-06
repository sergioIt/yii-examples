--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.8
-- Dumped by pg_dump version 10.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: supports_rules; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.supports_rules AS ENUM (
    'user',
    'admin',
    'moderator',
    'foreign_user'
);


--
-- Name: supports_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.supports_status AS ENUM (
    'work',
    'fired'
);


--
-- Name: on_update_current_timestamp_affected_customers_payments(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_affected_customers_payments() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;



CREATE FUNCTION public.on_update_current_timestamp_payments_bonus() RETURNS trigger
LANGUAGE plpgsql
AS $$
BEGIN
  NEW.updated = now();
  RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_app_events_log(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_app_events_log() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.created = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_asterisk_selected_customers(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_asterisk_selected_customers() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_bonuses(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_bonuses() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.date = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_card_customers_payments(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_card_customers_payments() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_cards(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_cards() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_customer_basis_rewards(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_customer_basis_rewards() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;




--
-- Name: on_update_current_timestamp_log_transits_manager(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_log_transits_manager() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.created = now();
   RETURN NEW;
END;
$$;


--
-- Name: on_update_current_timestamp_tasks(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.on_update_current_timestamp_tasks() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated = now();
   RETURN NEW;
END;
$$;


SET default_tablespace = '';

SET default_with_oids = false;


create table public.affected_customers
(
    id serial not null,
    created timestamp(0) default CURRENT_TIMESTAMP,
    updated timestamp(0) default CURRENT_TIMESTAMP,
    customer_id bigint not null,
    affected timestamp(0) not null,
    reset timestamp(0)
)
;

create function public.on_update_current_timestamp_affected_customers() returns trigger
    language plpgsql
as $$
BEGIN
NEW.updated = now();
RETURN NEW;
END;
$$
;

create trigger on_update_current_timestamp
before update
    on public.affected_customers
for each row
    execute procedure public.on_update_current_timestamp_affected_customers()
;


create table public.scripts_parts
(
    id serial not null
    constraint scripts_parts_pkey
    primary key,
    parent_id integer default 0,
    title varchar(128) not null,
    sort smallint
)
;

CREATE TABLE public.sessions (
  id CHARACTER(64) PRIMARY KEY NOT NULL,
  expire INTEGER,
  data BYTEA
);


create table public.source_message
(
    id serial not null
    constraint source_message_pkey
    primary key,
    category varchar(255),
    message text
)
;

create index idx_source_message_category
    on public.source_message (category)
;

create table public.message
(
    id integer not null
    constraint fk_message_source_message
    references public.source_message
    on update restrict on delete cascade,
    language varchar(16) not null,
    translation text,
    constraint pk_message_id_language
    primary key (id, language)
)
;

create index idx_message_language
    on public.message (language)
;

create table public.initial_deposits
(
  id serial not null
    constraint initial_deposits_pkey
    primary key,
  currency varchar(3) not null
    constraint initial_deposits_currency_key
    unique,
  value numeric(10,2)
);

INSERT INTO public.initial_deposits(id, currency, value)
    VALUES (1, 'THB', 10000);

CREATE TABLE public.admin_attached_users (
  id INTEGER PRIMARY KEY NOT NULL,
  admin_user_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL
);

CREATE UNIQUE INDEX uniq_admin_to_user_id ON public.admin_attached_users USING BTREE (admin_user_id, user_id);

--
-- Name: affected_customers_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.affected_customers_payments (
    id bigint NOT NULL,
    updated timestamp without time zone DEFAULT now() NOT NULL,
    payment_date timestamp without time zone NOT NULL,
    customer_id bigint NOT NULL,
    seller_id integer,
    payment_id bigint NOT NULL,
    amount numeric(10,2),
    currency  character varying(3) DEFAULT NULL,
   status SMALLINT NOT NULL DEFAULT '1'
);


--
-- Name: affected_customers_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.affected_customers_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: affected_customers_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.affected_customers_payments_id_seq OWNED BY public.affected_customers_payments.id;


--
-- Name: app_events_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_events_log (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    type_id bigint NOT NULL,
    user_id integer,
    customer_id bigint,
    scenario_id smallint,
    discriminant numeric(6,3),
    attempts smallint DEFAULT '0'::smallint NOT NULL,
    message text
);


--
-- Name: app_events_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_events_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_events_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.app_events_log_id_seq OWNED BY public.app_events_log.id;


--
-- Name: app_events_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_events_types (
    id bigint NOT NULL,
    name character varying(80) NOT NULL,
    description character varying(255)
);


--
-- Name: app_events_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_events_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_events_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.app_events_types_id_seq OWNED BY public.app_events_types.id;


--
-- Name: app_locales; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_locales (
    id bigint NOT NULL,
    name character varying(5) NOT NULL,
    currency character varying(5) NOT NULL,
    comment character varying(50)
);


--
-- Name: app_locales_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_locales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_locales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.app_locales_id_seq OWNED BY public.app_locales.id;


--
-- Name: app_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_settings (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    value smallint DEFAULT '0'::smallint,
    description character varying(255)
);


--
-- Name: app_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.app_settings_id_seq OWNED BY public.app_settings.id;


--
-- Name: asterisk_selected_customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asterisk_selected_customers (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now(),
    updated timestamp without time zone DEFAULT now() NOT NULL,
    customer_id bigint NOT NULL,
    bonus_amount numeric(10,2) NOT NULL,
    status smallint
);


--
-- Name: asterisk_selected_customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asterisk_selected_customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asterisk_selected_customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asterisk_selected_customers_id_seq OWNED BY public.asterisk_selected_customers.id;


--
-- Name: asterisk_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asterisk_settings (
    id bigint NOT NULL,
    min_operations bigint DEFAULT '0'::bigint,
    max_operations bigint DEFAULT '0'::bigint,
    mode smallint DEFAULT '0'::smallint,
    card_status smallint DEFAULT '0'::smallint,
    seller integer DEFAULT 0,
    bonus_amount numeric(10,2),
    has_card smallint DEFAULT '0'::smallint,
    "limit" bigint DEFAULT '0'::bigint,
    days_since_registration integer DEFAULT 0,
    hours_since_last_operation integer DEFAULT 100,
    use_post_filtering smallint DEFAULT '0'::smallint,
    use_reg_date smallint DEFAULT '0'::smallint
);


--
-- Name: asterisk_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asterisk_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asterisk_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asterisk_settings_id_seq OWNED BY public.asterisk_settings.id;


--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at bigint
);


--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_item (
    name character varying(64) NOT NULL,
    type bigint NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at bigint,
    updated_at bigint
);


--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


--
-- Name: auth_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_log (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    ua text,
    ip bigint,
    user_id integer,
    type integer NOT NULL
);


--
-- Name: auth_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.auth_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: auth_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.auth_log_id_seq OWNED BY public.auth_log.id;


--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at bigint,
    updated_at bigint
);


CREATE TABLE public.calls (
  id BIGINT PRIMARY KEY NOT NULL,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
  user_id INTEGER,
  phone_from CHARACTER VARYING(20),
  phone_to CHARACTER VARYING(20),
  duration INTEGER,
  status CHARACTER VARYING(16),
  record CHARACTER VARYING(255),
  type CHARACTER VARYING(16),
  country CHARACTER VARYING(3),
  method CHARACTER VARYING(16),
  customer_id INTEGER,
  country_id INTEGER,
  comment TEXT,
  closed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL::timestamp without time zone,
  row_id INTEGER,
  answer_waiting INTEGER,
  ivr INTEGER
);



create table public.calls_incoming_data
(
    id          serial not null primary key,
    phone       varchar(50),
    customer_id integer,
    type        integer,
    created     timestamp(0) default CURRENT_TIMESTAMP
);


--
-- Name: card_customers_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.card_customers_payments (
    id bigint NOT NULL,
    created timestamp with time zone DEFAULT now() NOT NULL,
    updated timestamp without time zone DEFAULT now() NOT NULL,
    payment_date timestamp without time zone NOT NULL,
    customer_id bigint NOT NULL,
    seller_id integer NOT NULL,
    payment_id bigint NOT NULL,
    amount numeric(10,2),
    affected_type smallint,
    currency  character varying(3) DEFAULT NULL,
    status SMALLINT NOT NULL DEFAULT '1'
);


--
-- Name: card_customers_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.card_customers_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: card_customers_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.card_customers_payments_id_seq OWNED BY public.card_customers_payments.id;


--
-- Name: cards; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cards (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now(),
    updated timestamp without time zone DEFAULT now(),
    affected_date timestamp without time zone,
    user_id integer,
    customer_id bigint NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    recall_date timestamp without time zone,
    decline_reason_id integer,
    opt_name character varying(100),
    opt_phone character varying(15),
    line_id character varying(50),
    line_number character varying(10),
    transit smallint,
    bo_experience_id smallint,
    auto_call smallint,
    first_name character varying(100),
    phone character varying(50),
    hot_keys JSONB
);

CREATE TABLE public.customers (
  id INTEGER PRIMARY KEY NOT NULL,
  updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  reg_date BIGINT,
  real SMALLINT NOT NULL DEFAULT 0,
  status SMALLINT NOT NULL DEFAULT 0,
  active SMALLINT NOT NULL DEFAULT 1,
  currency CHARACTER VARYING(3),
  email CHARACTER VARYING(254),
  phone CHARACTER VARYING(50),
  first_name CHARACTER VARYING(100),
  reg_device CHARACTER VARYING(12),
  reg_ip CHARACTER VARYING(15),
  sub1 CHARACTER VARYING(255),
  balance_demo NUMERIC(20,4),
  balance_real NUMERIC(20,4),
  last_operation_demo BIGINT,
  last_operation_real BIGINT,
  op_count_demo INTEGER DEFAULT 0,
  last_seen BIGINT,
  line_chat_init BOOLEAN,
  full_name_pp CHARACTER VARYING(255),
  last_name CHARACTER VARYING(100),
  locale CHARACTER VARYING(2),
  verify_denial_id SMALLINT,
  citizenship CHARACTER VARYING(5),
  comment CHARACTER VARYING(2000),
  email_confirmed INTEGER,
  phone_confirmed INTEGER,
  offline_pay_account_id INTEGER,
  ref_user_id INTEGER,
  pp_number CHARACTER VARYING(100),
  profit_percent INTEGER,
  phone_format CHARACTER VARYING(20),
  phone_valid BOOLEAN,
  deleted_at TIMESTAMP(0) WITHOUT TIME ZONE
);


INSERT INTO public.customers (id, updated, reg_date, real, status, active, currency, email, phone, first_name, reg_device, reg_ip, sub1, balance_demo, balance_real, last_operation_demo, last_operation_real, op_count_demo, last_seen, line_chat_init, full_name_pp, last_name, locale, verify_denial_id, citizenship, comment, email_confirmed, phone_confirmed, offline_pay_account_id, ref_user_id, pp_number, profit_percent)
VALUES (314017, '2018-12-28 14:32:11', 1520348242, 0, 3, 0, 'THB', 'serge.kite@gmail.com', '+442039363781', 'Sergio aewe', 'web_desktop', '192.168.89.224', null, null, 23.0000, 1537775749, 1536659529, null, 1541607332, null, null, null, null, null, null, null, null, null, null, null, null, null);

CREATE TABLE public.customers_actions (
  id serial PRIMARY KEY,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  customer_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  type CHARACTER VARYING(24) NOT NULL
);

CREATE TABLE public.customers_data (
  id serial PRIMARY KEY,
  customer_id INTEGER,
  last_tournament_operation BIGINT,
  last_refill_date TIMESTAMP(0) WITHOUT TIME ZONE,
  last_fillup_date TIMESTAMP(0) WITHOUT TIME ZONE,
  FOREIGN KEY (customer_id) REFERENCES public.customers (id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE
);




--
-- Name: cards_changes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cards_changes (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now(),
    card_id bigint NOT NULL,
    user_id integer,
    type smallint DEFAULT '1'::smallint,
    status smallint,
    recall_date TIMESTAMP(0) WITHOUT TIME ZONE
);


--
-- Name: cards_changes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cards_changes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cards_changes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cards_changes_id_seq OWNED BY public.cards_changes.id;


--
-- Name: cards_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cards_comments (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    card_id bigint NOT NULL,
    user_id integer,
    text text NOT NULL
);


--
-- Name: cards_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cards_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cards_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cards_comments_id_seq OWNED BY public.cards_comments.id;


--
-- Name: cards_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cards_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cards_id_seq OWNED BY public.cards.id;

CREATE SEQUENCE public.cards_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


CREATE TABLE public.cards_events (
  id INTEGER PRIMARY KEY NOT NULL,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  customer_id INTEGER,
  user_id INTEGER,
  type CHARACTER VARYING(16) NOT NULL,
  data JSONB
);


ALTER SEQUENCE public.cards_events_id_seq OWNED BY public.cards_events.id;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.countries (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    name character varying(16),
    code character(3),
    currency character varying(3),
    timezone character varying(32)
);


--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: countries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.countries_id_seq OWNED BY public.countries.id;


--
-- Name: customer_balance_eq_deposit_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customer_balance_eq_deposit_comments (
    id bigint NOT NULL,
    customer_id bigint,
    text text
);


--
-- Name: customer_balance_eq_deposit_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customer_balance_eq_deposit_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customer_balance_eq_deposit_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customer_balance_eq_deposit_comments_id_seq OWNED BY public.customer_balance_eq_deposit_comments.id;


--
-- Name: customer_basis_reward_calculate; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customer_basis_reward_calculate (
    customer_id bigint,
    affection_left bigint
);


--
-- Name: customer_basis_rewards; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customer_basis_rewards (
    id bigint NOT NULL,
    updated timestamp without time zone DEFAULT now() NOT NULL,
    affected timestamp without time zone,
    first_deposit_date timestamp without time zone,
    customer_id bigint NOT NULL,
    currency character varying(4),
    seller_id integer,
    delta numeric(10,2),
    payment_sum numeric(10,2),
    type smallint,
    extended smallint,
    reset_affected_date timestamp(0)
);


--
-- Name: customer_basis_rewards_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customer_basis_rewards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customer_basis_rewards_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customer_basis_rewards_id_seq OWNED BY public.customer_basis_rewards.id;




--
-- Name: customers_balance_eq_deposit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customers_balance_eq_deposit (
    id bigint NOT NULL,
    created timestamp with time zone DEFAULT now() NOT NULL,
    customer_id bigint NOT NULL,
    comment text,
    status smallint DEFAULT '0'::smallint NOT NULL,
    currency character varying(3)
);


--
-- Name: customers_balance_eq_deposit_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customers_balance_eq_deposit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customers_balance_eq_deposit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customers_balance_eq_deposit_id_seq OWNED BY public.customers_balance_eq_deposit.id;


--
-- Name: decline_reason; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.decline_reason (
    id integer NOT NULL,
    reason character varying(100) NOT NULL,
    show_to_supports smallint DEFAULT '1'::smallint NOT NULL
);


--
-- Name: decline_reason_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.decline_reason_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: decline_reason_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.decline_reason_id_seq OWNED BY public.decline_reason.id;


CREATE TABLE public.email_log (
  id serial not null PRIMARY KEY,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  customer_id INTEGER,
  email CHARACTER VARYING(50),
  address_book_id CHARACTER VARYING(50),
  message_type CHARACTER VARYING(50),
  message_locale CHARACTER VARYING(2),
  variables JSONB
);
--
-- Name: free_customers_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.free_customers_settings (
    id bigint NOT NULL,
    currency character varying(5),
    settings character varying(128) NOT NULL
);


--
-- Name: free_customers_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.free_customers_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: free_customers_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.free_customers_settings_id_seq OWNED BY public.free_customers_settings.id;





--
-- Name: log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.log (
    id bigint NOT NULL,
    level bigint,
    category character varying(255),
    log_time double precision,
    prefix text,
    message text
);


--
-- Name: log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.log_id_seq OWNED BY public.log.id;


--
-- Name: log_transits_manager; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.log_transits_manager (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    customer_id bigint NOT NULL,
    seller_id_old bigint NOT NULL,
    seller_id bigint NOT NULL,
    user_id bigint
);


--
-- Name: log_transits_manager_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.log_transits_manager_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: log_transits_manager_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.log_transits_manager_id_seq OWNED BY public.log_transits_manager.id;

CREATE SEQUENCE public.ip_blacklist_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.ip_blacklist (
  id INTEGER PRIMARY KEY NOT NULL,
  ip CHARACTER VARYING(15) NOT NULL
);

ALTER SEQUENCE public.ip_blacklist_id_seq OWNED BY public.ip_blacklist.id;

ALTER TABLE ONLY public.ip_blacklist ALTER COLUMN id SET DEFAULT nextval('public.ip_blacklist_id_seq'::regclass);

--
-- Name: migration; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time bigint
);


--
-- Name: next_card_common_rules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.next_card_common_rules (
    id bigint NOT NULL,
    common_rule character varying(500)
);


--
-- Name: next_card_common_rules_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.next_card_common_rules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: next_card_common_rules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.next_card_common_rules_id_seq OWNED BY public.next_card_common_rules.id;


--
-- Name: next_card_common_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.next_card_common_settings (
    id bigint NOT NULL,
    currency character varying(5),
    settings character varying(500) NOT NULL
);


--
-- Name: next_card_common_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.next_card_common_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: next_card_common_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.next_card_common_settings_id_seq OWNED BY public.next_card_common_settings.id;


--
-- Name: next_card_picked_customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.next_card_picked_customers (
    id bigint NOT NULL,
    created timestamp with time zone DEFAULT now() NOT NULL,
    customer_id bigint NOT NULL,
    scenario_id smallint
);


--
-- Name: next_card_picked_customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.next_card_picked_customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: next_card_picked_customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.next_card_picked_customers_id_seq OWNED BY public.next_card_picked_customers.id;


--
-- Name: next_card_user_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.next_card_user_settings (
    id bigint NOT NULL,
    user_id integer,
    rule character varying(500)
);


--
-- Name: next_card_user_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.next_card_user_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: next_card_user_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.next_card_user_settings_id_seq OWNED BY public.next_card_user_settings.id;


create table public.payments
(
    id serial not null PRIMARY KEY,
    created bigint,
    updated bigint,
    customer_id bigint,
    billing varchar(255),
    paid_date bigint,
    status smallint,
    currency varchar(3),
    amount numeric(10,2),
    is_first SMALLINT default 0,
    deposit TIMESTAMP(0),
    changed TIMESTAMP(0),
    additional BOOLEAN
)
;

create table public.payments_bonus
(
  payment_id int,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  support_id int,
  first_deposit TIMESTAMP(0),
  type INT,
  bonus numeric(10,2),
  is_transit BOOLEAN
)
;

CREATE TABLE public.queues (
  id INTEGER PRIMARY KEY NOT NULL,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  description TEXT,
  short_name CHARACTER VARYING(32),
  enabled BOOLEAN DEFAULT true
);
CREATE UNIQUE INDEX queues_short_name_key ON public.queues USING BTREE (short_name);


CREATE TABLE public.queue_users (
  id INTEGER PRIMARY KEY NOT NULL,
  updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  user_id INTEGER NOT NULL,
  queue_id INTEGER NOT NULL,
  sort SMALLINT NOT NULL DEFAULT 0,
  enabled BOOLEAN
);

INSERT INTO public.queues (id, created, description, short_name, enabled)

VALUES (1, '2018-10-25 22:53:10', 'new demo customers sorted by date and discriminant', 'demo_common', TRUE),
  (2, '2018-10-25 22:53:10',   'карточки, закрепленные за конкретным селлером в статусе In Progress и Deposit (оба mode DEMO), у которых подошло время перезвона',
   'demo_ip_app_recall', TRUE),
  (4, '2018-10-25 22:53:10', 'in progress (demo), Deposit(demo), NOP (demo) без даты перезвона',
   'demo_ip_app_empty_recall', TRUE),
  (5, '2018-10-25 22:53:10', 'real_asleep с датой перезвона?', 'real_stopped', TRUE),
  (6, '2018-10-25 22:53:10', 'real inactive с датой перезвона (?)', 'real_inactive', TRUE),
  (8, '2018-10-25 22:53:10', 'real no trades с датой перезвона (?)', 'demo_nop_common', TRUE),
  (9, '2018-10-25 22:53:10', 'real no trades с датой перезвона (?)', 'real_demo_active', TRUE);


INSERT INTO public.queue_users (id, updated, user_id, queue_id, sort, enabled)
VALUES
  (2, '2018-10-25 22:53:10', 3, 1, 1, TRUE),
  (4, '2018-10-25 22:53:10', 3, 2, 4, TRUE),
  (5, '2018-10-25 22:53:10', 3, 4, 5, TRUE),
  (6, '2018-10-25 22:53:10', 3, 5, 6, TRUE),
  (7, '2018-10-25 22:53:10', 3, 6, 7, TRUE),
  (1, '2018-10-25 22:53:10', 3, 8, 2, TRUE),
  (8, '2018-10-25 22:53:10', 3, 9, 2, TRUE);

--
-- Name: supports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.supports (
    id integer NOT NULL,
    asterisk_id integer,
    country_id bigint,
    admin_id bigint,
    telegram_chat_id character varying(16),
    login character varying(100) NOT NULL,
    pass character varying(40),
    salt character varying(32),
    color character varying(10),
    status public.supports_status DEFAULT 'work'::public.supports_status NOT NULL,
    rules public.supports_rules DEFAULT 'user'::public.supports_rules NOT NULL,
    ip character varying(15),
    fio character varying(100),
    autocall smallint DEFAULT '0'::smallint NOT NULL,
    last_login timestamp without time zone,
    reg_date timestamp with time zone DEFAULT now() NOT NULL,
    access_token character varying(32),
    locale character varying(5),
    currencies character varying(200),
    force_logout boolean,
    fired_date TIMESTAMP(0),
    allow_call_list BOOLEAN DEFAULT false,
    asterisk_countries JSONB
);


--
-- Name: supports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.supports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: supports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.supports_id_seq OWNED BY public.supports.id;

CREATE TABLE public.sms_log (
  id serial not null PRIMARY key,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  customer_id INTEGER,
  phone CHARACTER VARYING(20),
  text TEXT,
  type CHARACTER VARYING(16),
  language CHARACTER VARYING(2),
  status CHARACTER VARYING(16),
  service CHARACTER VARYING(16),
  system_info CHARACTER VARYING(255)
);
--
-- Name: tasks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now(),
    updated timestamp without time zone DEFAULT now(),
    group_id bigint,
    customer_id bigint,
    creator_id integer NOT NULL,
    appointed_to_id integer NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    email character varying(100),
    text text,
    telegram_notified smallint,
    currency character varying(5),
    priority SMALLINT
);

INSERT INTO public.tasks (id, created, updated, group_id, customer_id, creator_id, appointed_to_id, status, email, text, telegram_notified, currency, priority)
VALUES
  (1, null, null, 2, 766553, 1, 5, 1, null, 'customer notified (for super-admin)', 0, 'VND', 2),
  (2, null,null, 2, 766553, 5, 1, 1, null, 'task from super-admin', 0, 'VND', 2),
  (3, null, '2018-10-25 22:53:10', 2, 766553, 1, 5, 2, null, 'task#3 (for super-admin)', 0, 'VND', 2),
  (4, null, '2018-10-25 22:53:10', 2, 766553, 1, 5, 3, null, 'task4 (for super-admin)', 0, 'VND', 2);
--
-- Name: tasks_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks_comments (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    task_id bigint NOT NULL,
    user_id integer,
    text text NOT NULL
);

INSERT INTO public.tasks_comments (id,
                                   created,
                                   task_id,
                                   user_id,
                                   text
)
VALUES
  ( 1, '2018-10-25 22:53:10', 1, 1, 'first comment'),
  (2, '2018-10-26 22:53:10', 1, 1, 'email was sent'),
  (3, '2018-10-26 22:53:10', 1, 5, 'comment for #task2');

--
-- Name: tasks_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_comments_id_seq OWNED BY public.tasks_comments.id;


--
-- Name: tasks_files; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks_files (
    id bigint NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    task_id bigint NOT NULL,
    url character varying(255),
    name character varying(255),
    size bigint NOT NULL
);


--
-- Name: tasks_files_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_files_id_seq OWNED BY public.tasks_files.id;


--
-- Name: tasks_groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks_groups (
    id bigint NOT NULL,
    name character varying(24)
);

CREATE TABLE public.tasks_groups_link (
  task_id INTEGER,
  group_id INTEGER
);

INSERT INTO public.tasks_groups_link( task_id, group_id)
    VALUES
      ( 1, 1),
      ( 2, 1),
      ( 3, 1);
--
-- Name: tasks_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_groups_id_seq OWNED BY public.tasks_groups.id;


--
-- Name: tasks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_id_seq OWNED BY public.tasks.id;

--
-- CREATE SEQUENCE public.tournament_invitation_log_id_seq
-- START WITH 1
-- INCREMENT BY 1
-- NO MINVALUE
-- NO MAXVALUE
-- CACHE 1;

CREATE TABLE public.tournament_invitation_log (
  id serial primary key,
  created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  customer_id INTEGER,
  user_id INTEGER,
  status CHARACTER VARYING(16),
  info CHARACTER VARYING(255)
);

CREATE TABLE public.tournaments (
  id serial primary key,
  type CHARACTER VARYING(10) NOT NULL
);

CREATE TABLE public.tournaments_accounts (
  id serial PRIMARY KEY,
  customer_id INTEGER,
  tournament_id INTEGER,
  FOREIGN KEY (customer_id) REFERENCES public.customers (id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE,
  FOREIGN KEY (tournament_id) REFERENCES public.tournaments (id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE
);


-- ALTER SEQUENCE public.tournament_invitation_log_id_seq OWNED BY public.tournament_invitation_log.id;

-- ALTER TABLE ONLY public.tournament_invitation_log ALTER COLUMN id SET DEFAULT nextval('public.tournament_invitation_log_id_seq'::regclass);

-- ALTER TABLE ONLY public.tournament_invitation_log  ADD CONSTRAINT tournament_invitation_log_pkey PRIMARY KEY (id);
--
-- Name: verification_stat_by_day; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.verification_stat_by_day (
    id bigint NOT NULL,
    day date NOT NULL,
    mode smallint,
    admin_id bigint,
    sent bigint,
    "unique" bigint,
    first_request bigint,
    approved bigint,
    declined bigint,
    skipped bigint,
    is_complete smallint,
    currency character varying(3)
);


--
-- Name: verification_stat_by_day_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.verification_stat_by_day_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: verification_stat_by_day_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.verification_stat_by_day_id_seq OWNED BY public.verification_stat_by_day.id;


--
-- Name: affected_customers_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affected_customers_payments ALTER COLUMN id SET DEFAULT nextval('public.affected_customers_payments_id_seq'::regclass);


--
-- Name: app_events_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_log ALTER COLUMN id SET DEFAULT nextval('public.app_events_log_id_seq'::regclass);


--
-- Name: app_events_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_types ALTER COLUMN id SET DEFAULT nextval('public.app_events_types_id_seq'::regclass);


--
-- Name: app_locales id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_locales ALTER COLUMN id SET DEFAULT nextval('public.app_locales_id_seq'::regclass);


--
-- Name: app_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_settings ALTER COLUMN id SET DEFAULT nextval('public.app_settings_id_seq'::regclass);


--
-- Name: asterisk_selected_customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asterisk_selected_customers ALTER COLUMN id SET DEFAULT nextval('public.asterisk_selected_customers_id_seq'::regclass);


--
-- Name: asterisk_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asterisk_settings ALTER COLUMN id SET DEFAULT nextval('public.asterisk_settings_id_seq'::regclass);


--
-- Name: auth_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_log ALTER COLUMN id SET DEFAULT nextval('public.auth_log_id_seq'::regclass);






--
-- Name: card_customers_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.card_customers_payments ALTER COLUMN id SET DEFAULT nextval('public.card_customers_payments_id_seq'::regclass);


--
-- Name: cards id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards ALTER COLUMN id SET DEFAULT nextval('public.cards_id_seq'::regclass);


--
-- Name: cards_changes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_changes ALTER COLUMN id SET DEFAULT nextval('public.cards_changes_id_seq'::regclass);


--
-- Name: cards_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_comments ALTER COLUMN id SET DEFAULT nextval('public.cards_comments_id_seq'::regclass);

ALTER TABLE ONLY public.cards_events ALTER COLUMN id SET DEFAULT nextval('public.cards_events_id_seq'::regclass);


--
-- Name: countries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.countries ALTER COLUMN id SET DEFAULT nextval('public.countries_id_seq'::regclass);


--
-- Name: customer_balance_eq_deposit_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_balance_eq_deposit_comments ALTER COLUMN id SET DEFAULT nextval('public.customer_balance_eq_deposit_comments_id_seq'::regclass);


--
-- Name: customer_basis_rewards id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_basis_rewards ALTER COLUMN id SET DEFAULT nextval('public.customer_basis_rewards_id_seq'::regclass);




--
-- Name: customers_balance_eq_deposit id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers_balance_eq_deposit ALTER COLUMN id SET DEFAULT nextval('public.customers_balance_eq_deposit_id_seq'::regclass);


--
-- Name: decline_reason id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decline_reason ALTER COLUMN id SET DEFAULT nextval('public.decline_reason_id_seq'::regclass);


--
-- Name: free_customers_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.free_customers_settings ALTER COLUMN id SET DEFAULT nextval('public.free_customers_settings_id_seq'::regclass);


-- Name: log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.log ALTER COLUMN id SET DEFAULT nextval('public.log_id_seq'::regclass);


--
-- Name: log_transits_manager id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.log_transits_manager ALTER COLUMN id SET DEFAULT nextval('public.log_transits_manager_id_seq'::regclass);


--
-- Name: next_card_common_rules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_common_rules ALTER COLUMN id SET DEFAULT nextval('public.next_card_common_rules_id_seq'::regclass);


--
-- Name: next_card_common_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_common_settings ALTER COLUMN id SET DEFAULT nextval('public.next_card_common_settings_id_seq'::regclass);


--
-- Name: next_card_picked_customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_picked_customers ALTER COLUMN id SET DEFAULT nextval('public.next_card_picked_customers_id_seq'::regclass);


--
-- Name: next_card_user_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_user_settings ALTER COLUMN id SET DEFAULT nextval('public.next_card_user_settings_id_seq'::regclass);



--
-- Name: supports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.supports ALTER COLUMN id SET DEFAULT nextval('public.supports_id_seq'::regclass);


--
-- Name: tasks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks ALTER COLUMN id SET DEFAULT nextval('public.tasks_id_seq'::regclass);


--
-- Name: tasks_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_comments ALTER COLUMN id SET DEFAULT nextval('public.tasks_comments_id_seq'::regclass);


--
-- Name: tasks_files id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_files ALTER COLUMN id SET DEFAULT nextval('public.tasks_files_id_seq'::regclass);


--
-- Name: tasks_groups id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_groups ALTER COLUMN id SET DEFAULT nextval('public.tasks_groups_id_seq'::regclass);

-- ALTER TABLE ONLY public.tournament_invitation_log ALTER COLUMN id SET DEFAULT nextval('public.tournament_invitation_log_id_seq'::regclass);
--
-- Name: verification_stat_by_day id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_stat_by_day ALTER COLUMN id SET DEFAULT nextval('public.verification_stat_by_day_id_seq'::regclass);



--
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.auth_assignment (item_name, user_id, created_at) FROM stdin;
admin	1	1479211456
admin	2	1479211455
bonus.all	5	1479211456
customer.free-discriminant	5	1479211456
customer.resendSmsWithPhoneVerificationCode	5	1511262733
receiveAdminReward	2	1486571098
seller	113	1486545266
seller	3	1479211455
seller	4	1479211456
super-admin	5	1479211456
super-admin	6	1479211456
support	7	1479211456
support	8	1479211456
task-manager	10	1479211456
task-manager	9	1479211456
\.


--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.auth_item (name, type, description, rule_name, data, created_at, updated_at) FROM stdin;
admin	1	\N	\N	\N	1479211455	1479211455
affected-customer.update	2	Update affected customer record (enter update mode)	\N	\N	1516288707	1516288707
affected-customer.update-affected-date	2	Update affected customer's affected_date attribute	\N	\N	1516288707	1516288707
affected-payment.list	2	View Affected payments list page	\N	\N	1519305597	1519305597
app.switchAppLocale	2	Switch app locales (if they are exists)	\N	\N	1501756137	1501756137
archiveTask	2	Archive task	\N	\N	1480687966	1480687966
asterisk.*	2	All asterisk actions	\N	\N	1492610860	1492610860
asterisk.list-selected-customers	2	List asterisk-processed users	\N	\N	1492610860	1492610860
asterisk.settings-update	2	Update asterisk settings	\N	\N	1492610860	1492610860
bonus-add	2	Add new bonus	\N	\N	1486564492	1486564492
bonus-add-admin	2	Add new bonus for admin	\N	\N	1486573471	1486573471
bonus-list	2	List of all bonuses	\N	\N	1486564492	1486564492
bonus-list.filterByCountry	2	Filtering bonus/list by available countries	\N	\N	1513346473	1513346473
bonus-manager	2	Bonus manager	\N	\N	1486564492	1486564492
bonus-manager.filterByCountry	2	Filter by country at bonus manager	\N	\N	1510153725	1510153725
bonus.all	2	All bonuses permissions	\N	\N	1486564492	1486564492
bonus.for-admin	2	Group of permissions of bonus actions for admin role	\N	\N	1513346473	1513346473
bonus.for-super-admin	2	Group of permissions of bonus actions for super-admin role	\N	\N	1513346473	1513346473
bonus.settings-update	2	Update bonus settings	\N	\N	1493303405	1493303405
call.for-admin	2	Group of call actions permissions for role admin	\N	\N	1513350887	1513350887
call.for-seller	2	Group of call actions permissions for role seller	\N	\N	1513350887	1513350887
call.for-support	2	Group of call permission for support	\N	\N	1521709297	1521709297
call.listener-support	2	Call listener page for support	\N	\N	1521709297	1521709297
call.for-super-admin	2	Group of call actions permissions for role super-admin	\N	\N	1513350887	1513350887
call.get-status	2	Get status of external call engine at page call/status	\N	\N	1513350887	1513350887
call.listener	2	Action for page listening incoming calls from asterisk and produce new cards based on them	\N	\N	1513350887	1513350887
call.reboot	2	Reboot external call engine	\N	\N	1513350887	1513350887
call.set-multiplier	2	Set number of lines of external call engine	\N	\N	1513350887	1513350887
call.set-status	2	Set status of external call engine (on/off)	\N	\N	1513350887	1513350887
call.status	2	Call engine status page	\N	\N	1513350887	1513350887
call.status.controls	2	Panel with control buttons at call/status page	\N	\N	1513350887	1513350887
call.timeline	2	Call timeline visualisation page	\N	\N	1519141510	1519141510
card-actions.filterByCountry	2	Filter by country at card actions list	\N	\N	1511539336	1511539336
card.assign-single	2	Assign singe card to specified seller	\N	\N	1505309758	1505309758
card.click-to-call	2	Use 'click to call' button at card or customer view	\N	\N	1502189083	1502189083
card.createFromSearch	2	Create new card from card search result	\N	\N	1489509043	1489509043
card.createFromTaskList	2	Create new card from task list	\N	\N	1504519310	1504519310
card.for-admin	2	Card permissions group specified for admin	\N	\N	1487692138	1487692138
card.for-seller	2	Card permissions group specified for seller	\N	\N	1487692137	1487692137
card.for-super-admin	2	Card permissions group specified for super-admin	\N	\N	1487692138	1487692138
card.for-support	2	Card permissions group specified for support	\N	\N	1487692138	1487692138
card.getSiamOptionPanelLink	2	User can get link to card to external siamoption admin panel instead of internal link to card	\N	\N	1487759425	1487759425
card.list	2	List all existed cards	\N	\N	1487692138	1487692138
card.list-actions	2	List all existed actions with cards	\N	\N	1487692138	1487692138
card.list-actions-my	2	List all existed actions with sellers's cards	\N	\N	1487692138	1487692138
card.list-my	2	List all seller's cards	\N	\N	1487692138	1487692138
card.list-my-not-vip-need-for-recall	2	Lis all not vip customers cards that needs for recall	\N	\N	1504073077	1504073077
card.list-my-profitable	2	List of seller's cards of customers which brings rewards to seller	\N	\N	1495529374	1495529374
card.list-my-vip	2	List all seller's vip cards	\N	\N	1487692138	1487692138
card.list-my-vip-need-for-recall	2	Lis all vip customers cards that needs for recall	\N	\N	1488201547	1488201547
card.list-vip	2	Lis all vip customers cards	\N	\N	1488190854	1488190854
card.manage-fired	2	Manage cards between fired and active sellers	\N	\N	1505309758	1505309758
card.next	2	Get next card	\N	\N	1487692138	1487692138
card.payments.viewAll	2	View all payment (in any status) in card view payments	\N	\N	1487760104	1487760104
card.search	2	Search for cards	\N	\N	1487692138	1487692138
card.transits-manager-assign-selected	2	Assign selected cards to specified seller	\N	\N	1505400656	1505400656
card.transits-manager-assign-single	2	Assign singe card to specified seller	\N	\N	1505400656	1505400656
card.transits-manager-group	2	Group of all permission to manage cards transits from fired to active sellers	\N	\N	1505400656	1505400656
card.transits-manager-list	2	List of cards to manage from fired to active sellers	\N	\N	1505400656	1505400656
createSupport	2	Create support	\N	\N	1482159084	1482159084
createTask	2	Create new task	\N	\N	1479211455	1479211455
customer-balance-eq-deposit	2	Manage customers with balance is equal to deposit	\N	\N	1484166713	1484166713
customer-balance-eq-deposit.list-cards-all	2	List of all cards with balance equal to deposit	\N	\N	1487692139	1487692139
customer-balance-eq-deposit.list-cards-my	2	List of seller's cards with balance equal to deposit	\N	\N	1487692139	1487692139
customer-balance-eq-deposit.list-no-cards	2	List of all customer's without cards and with balance equal deposit	\N	\N	1487692141	1487692141
stat.payment.first-deposit	2	First payment statistics	\N	\N	1487692145	1487692145
customer-balance-eq-deposit.no-cards-notifier	2	View navbar notifier about customer with balance=deposit and having no cards	\N	\N	1488881398	1488881398
customer-reward-reference-list	2	List of all collected customer reference values for support rewards	\N	\N	1486564493	1486564493
customer.duplicates-by-cookies	2	List of all customer's duplicates defined by cookies	\N	\N	1487692141	1487692141
customer.duplicates-by-ip-and-device	2	List of all customer's duplicates defined by ip and device	\N	\N	1487692142	1487692142
customer.duplicates-by-passport	2	List of all customer's duplicates defined by passport	\N	\N	1487692142	1487692142
customer.for-admin	2	Customer permissions group specified for admin	\N	\N	1487692141	1487692141
customer.for-support	2	Customer permissions group specified for support	\N	\N	1487692141	1487692141
customer.free	2	List of free customers	\N	\N	1487692141	1487692141
customer.free-discriminant	2	List of free customers ordered by date desc, by discriminant inside date	\N	\N	1496651677	1496651677
customer.free-duplicates	2	All asterisk actions	\N	\N	1493805811	1493805811
customer.list	2	List of all customers	\N	\N	1487692141	1487692141
customer.operations	2	List of all customer's operations	\N	\N	1487692141	1487692141
customer.resendSmsWithPhoneVerificationCode	2	Resend sms to customer with phone verification code and for button visibility	\N	\N	1511262673	1511262673
customer.viewComment	2	View comment for customer in customer panel view	\N	\N	1499939677	1499939677
dashboard.created-updated-cards-panel	2	Dashboard panel with seller's recommended bonus for current period	\N	\N	1505470901	1505470901
dashboard.for-admin	2	Group of dashboard permissions for role admin	\N	\N	1505470901	1505470901
dashboard.for-seller	2	Group of dashboard permissions for role seller	\N	\N	1505470901	1505470901
dashboard.for-super-admin	2	Group of dashboard permissions for role super-admin	\N	\N	1505470901	1505470901
dashboard.for-support	2	Group of dashboard permissions for role support	\N	\N	1505470901	1505470901
dashboard.for-task-manager	2	Group of dashboard permissions for role task manager	\N	\N	1505470901	1505470901
dashboard.recommended-bonus-panel	2	Dashboard panel with seller's recommended bonus for current period	\N	\N	1505470901	1505470901
dashboard.sellers-capacity-panel	2	Dashboard panel with counters of available customers for different periods + today total created/update cards counters	\N	\N	1505470901	1505470901
free-customer-setting.update	2	Update free customer settings	\N	\N	1495100658	1495100658
missed-call-manager	2	Manage missed call: allows list, write comment and hide	\N	\N	1486643627	1486643627
next-card-rules.*	2	Next card rules: all possible actions	\N	\N	1493805811	1493805811
next-card-rules.create	2	Create next card rule for specified seller	\N	\N	1493805811	1493805811
next-card-rules.update	2	Update next card rule for specified seller	\N	\N	1493805811	1493805811
next-card-rules.update-common	2	Update common next card rule (applied for all sellers)	\N	\N	1493805811	1493805811
payment-list	2	List of all customers payments	\N	\N	1486461558	1486461558
rbac.*	2	All rbac actions	\N	\N	1493216633	1493216633
rbac.list-assignment	2	List rbac assignments for users	\N	\N	1493216633	1493216633
rbac.list-items	2	List all existed rbac auth items	\N	\N	1493216633	1493216633
rbac.list-parent-child	2	List rbac parent-child pairs	\N	\N	1493216633	1493216633
receiveAdminReward	2	Permission to able user to get special admin reward based on customers rewards	\N	\N	1486571094	1486571094
seller	1	\N	\N	\N	1479211455	1479211455
settings.bonus	2	Bonus settings manager	\N	\N	1493303405	1493303405
settings.free-customer	2	View and change settings for free customer	\N	\N	1487692143	1487692143
settings.next-card	2	Next card scenarios settings	\N	\N	1493805811	1493805811
stat.bonus.affected-payments	2	Bonus statistics: affected payments by sellers and days	\N	\N	1512124000	1512124000
stat.bonus.affected-payments-all	2	All affected payments stat: all payments attached to affected customers	\N	\N	1518098629	1518098629
stat.bonus.affected-payments-my	2	Bonus statistics: affected payments by days for seller	\N	\N	1512124000	1512124000
stat.bonus.affected-payments.filterByCardStatus	2	Bonus affected payments stat: filter by card status	\N	\N	1517497787	1517497787
stat.bonus.affected-payments.filterByCountry	2	Bonus statistics: affected payments filter by country	\N	\N	1512124000	1512124000
stat.customer.source	2	Customer by source statistics	\N	\N	1487692145	1487692145
stat.customer.verification	2	Customer verification statistics	\N	\N	1487692145	1487692145
stat.for-admin	2	Group of permissions of statistics for admin	\N	\N	1487692145	1487692145
stat.for-seller	2	Group of permissions of statistics for seller	\N	\N	1487692145	1487692145
stat.for-super-admin	2	All statistics permissions allowed for super-admin role	\N	\N	1488790118	1488790118
stat.for-task-manager	2	Statistics permissions group for role task-manager	\N	\N	1498815578	1498815578
stat.operations	2	Operations statistics	\N	\N	1487692145	1487692145
stat.payment-verification.actions-daily	2	Payment verification actions lists for specified day groped by admin	\N	\N	1499939677	1499939677
stat.payment-verification.actions-list	2	Payment verification changes full list	\N	\N	1499939677	1499939677
stat.payment-verification.by-admin	2	Payment verification statistics grouped by admin	\N	\N	1499939677	1499939677
stat.payment-verification.by-day	2	Payment verification statistics grouped by day	\N	\N	1499939677	1499939677
stat.payment.card-payments	2	Composed statistics of all payments of customer attached to existed card	\N	\N	1520875154	1520875154
stat.seller.actions-all	2	All seller's actions statistics	\N	\N	1487692145	1487692145
stat.seller.actions-all.filterByCountry	2	Filter by country at sellers actions summary	\N	\N	1512124000	1512124000
stat.seller.actions-daily	2	All seller's actions daily	\N	\N	1487692145	1487692145
stat.seller.actions-daily.filterByCountry	2	Filter by country at sellers actions daily	\N	\N	1513958053	1513958053
stat.seller.actions-personal	2	Seller actions statistics	\N	\N	1487692145	1487692145
stat.seller.actions-total	2	All seller's actions total	\N	\N	1487692145	1487692145
stat.seller.actions-total.filterByCountry	2	Filter by country at sellers-statistics/actions-total	\N	\N	1513949740	1513949740
stat.verification.actions-daily	2	Admin  verification actions lists for specified day groped by admin	\N	\N	1494493896	1494493896
stat.verification.actions-daily-personal	2	Daily verification statistics by current user	\N	\N	1498815578	1498815578
stat.verification.actions-list	2	Users verifications status changes full list	\N	\N	1494493896	1494493896
stat.verification.by-admin	2	Verification statistics grouped by admin	\N	\N	1494493896	1494493896
stat.verification.by-admin-personal	2	Personal verification statistics by current user for specified period	\N	\N	1498815578	1498815578
stat.verification.by-day	2	Verification statistics grouped by day	\N	\N	1494493896	1494493896
stat.withdraw.processing	2	Withdraw processing statistics	\N	\N	1488790118	1488790118
super-admin	1	Can change system settings, view system log, view users; do not interact with customers	\N	\N	1487692136	1487692136
support	1	Support: do not directly interact with customers by phone	\N	\N	1487692136	1487692136
system.app-log	2	View application log	\N	\N	1487692143	1487692143
system.auth-log	2	View auth log	\N	\N	1505987361	1505987361
system.events-log	2	View specific events log	\N	\N	1487692144	1487692144
system.for-super-admin	2	Group of system permissions for role super-admin	\N	\N	1505987361	1505987361
system.log-transit-manager	2	Log transit manager permission	\N	\N	1508153346	1508153346
system.php-info	2	View phpinfo page	\N	\N	1495118210	1495118210
task-manager	1	Task manager role	\N	\N	1491903591	1491903591
task.all	2	All task's permissions	\N	\N	1487692136	1487692136
task.common	2	All common task's permissions	\N	\N	1487692136	1487692136
task.updateAnyTask	2	Update any task	\N	\N	1487759425	1487759425
tools.for-seller	2	Tools permission group for role seller	\N	\N	1501064571	1501064571
tools.for-support	2	Tools permission group for role support	\N	\N	1501064571	1501064571
tools.line-chat	2	Permission for Line Chat	\N	\N	1501064571	1501064571
updateCard	2	Update card	\N	\N	1480939035	1480939035
updateOwnCard	2	Update card by it's owner	isCardAuthor	\N	1480939035	1480939035
updateOwnTask	2	Update own task	isTaskAuthor	\N	1479211455	1479211455
updateSupport	2	Update support	\N	\N	1481194766	1481194766
updateTask	2	Update task	\N	\N	1479211455	1479211455
user.assignRole	2	Assign role to user	\N	\N	1498474327	1498474327
user.assignRole.Admin	2	Assign role 'admin' to user	\N	\N	1498474327	1498474327
user.assignRole.SuperAdmin	2	Assign role 'super-admin' to user	\N	\N	1498474327	1498474327
user.changeColor	2	Permission to change user color	\N	\N	1504073077	1504073077
user.changeCountry	2	Change user country	\N	\N	1508153346	1508153346
user.for-admin	2	User permissions group for admin role	\N	\N	1498474327	1498474327
user.for-super-admin	2	User permissions group for super-admin role	\N	\N	1498474327	1498474327
user.list	2	List of users	\N	\N	1498474327	1498474327
user.save	2	Save existed user in update mode	\N	\N	1498474327	1498474327
user.save-new	2	Save (create) new user	\N	\N	1498474327	1498474327
user.update	2	Update user info (need additional permissions for each attribute for update)	\N	\N	1493894646	1493894646
user.update-asterisk-id	2	Update asterisk id	\N	\N	1505206847	1505206847
user.update-currencies	2	Update user's currencies set	\N	\N	1511452328	1511452328
user.update-login	2	Update (change) user login	\N	\N	1493894646	1493894646
user.update-status	2	Update (change) user status	\N	\N	1493894647	1493894647
user.view	2	View user info	\N	\N	1498474327	1498474327
withdraw-list	2	List of all customers withdraw	\N	\N	1486461558	1486461558
customer.call-history	2	View customer calls history (at modal view tab)	\N	\N	1528121120	1528121120
\.


--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.auth_item_child (parent, child) FROM stdin;
bonus.for-super-admin	affected-customer.update
bonus.for-super-admin	affected-customer.update-affected-date
bonus.for-super-admin	affected-payment.list
admin	app.switchAppLocale
super-admin	app.switchAppLocale
task.all	archiveTask
super-admin	asterisk.*
asterisk.*	asterisk.list-selected-customers
asterisk.*	asterisk.settings-update
bonus.for-admin	bonus-add
bonus.for-super-admin	bonus-add
bonus.for-admin	bonus-add-admin
bonus.for-super-admin	bonus-add-admin
bonus.for-admin	bonus-list
bonus.for-super-admin	bonus-list
bonus.for-super-admin	bonus-list.filterByCountry
bonus.for-admin	bonus-manager
bonus.for-super-admin	bonus-manager
bonus.for-super-admin	bonus-manager.filterByCountry
super-admin	bonus-manager.filterByCountry
admin	bonus.for-admin
super-admin	bonus.for-super-admin
bonus.for-super-admin	bonus.settings-update
admin	call.for-admin
call.for-super-admin	call.for-admin
seller	call.for-seller
support	call.for-support
super-admin	call.for-super-admin
call.status.controls	call.get-status
call.for-seller	call.listener
call.for-support	call.listener-support
call.status.controls	call.reboot
call.status.controls	call.set-multiplier
call.status.controls	call.set-status
call.for-admin	call.status
call.for-admin	call.status.controls
call.for-admin	call.timeline
stat.for-super-admin	card-actions.filterByCountry
card.for-super-admin	card.assign-single
card.for-admin	card.click-to-call
card.for-seller	card.click-to-call
card.for-support	card.click-to-call
card.for-seller	card.createFromSearch
card.for-seller	card.createFromTaskList
admin	card.for-admin
super-admin	card.for-admin
seller	card.for-seller
super-admin	card.for-super-admin
support	card.for-support
super-admin	card.getSiamOptionPanelLink
card.for-admin	card.list
card.for-admin	card.list-actions
card.for-seller	card.list-actions-my
card.for-seller	card.list-my
card.for-seller	card.list-my-not-vip-need-for-recall
card.for-seller	card.list-my-profitable
card.for-seller	card.list-my-vip
card.for-seller	card.list-my-vip-need-for-recall
card.for-admin	card.list-vip
card.for-super-admin	card.manage-fired
card.for-seller	card.next
admin	card.payments.viewAll
super-admin	card.payments.viewAll
card.for-admin	card.search
card.for-seller	card.search
card.for-support	card.search
card.transits-manager-group	card.transits-manager-assign-selected
card.transits-manager-group	card.transits-manager-assign-single
card.for-admin	card.transits-manager-group
card.for-super-admin	card.transits-manager-group
card.transits-manager-group	card.transits-manager-list
super-admin	createSupport
seller	createTask
task.common	createTask
admin	customer-balance-eq-deposit
card.for-admin	customer-balance-eq-deposit.list-cards-all
card.for-support	customer-balance-eq-deposit.list-cards-all
card.for-seller	customer-balance-eq-deposit.list-cards-my
customer.for-admin	customer-balance-eq-deposit.list-no-cards
customer.for-support	customer-balance-eq-deposit.list-no-cards
customer.for-support	customer-balance-eq-deposit.no-cards-notifier
bonus.for-admin	customer-reward-reference-list
bonus.for-super-admin	customer-reward-reference-list
customer.for-admin	customer.duplicates-by-cookies
customer.for-admin	customer.duplicates-by-ip-and-device
customer.for-admin	customer.duplicates-by-passport
admin	customer.for-admin
super-admin	customer.for-admin
support	customer.for-support
customer.for-admin	customer.free
customer.for-admin	customer.free-duplicates
customer.for-admin	customer.list
customer.for-admin	customer.operations
admin	customer.viewComment
super-admin	customer.viewComment
dashboard.for-seller	dashboard.created-updated-cards-panel
admin	dashboard.for-admin
seller	dashboard.for-seller
super-admin	dashboard.for-super-admin
support	dashboard.for-support
task-manager	dashboard.for-task-manager
dashboard.for-seller	dashboard.recommended-bonus-panel
dashboard.for-super-admin	dashboard.sellers-capacity-panel
admin	free-customer-setting.update
super-admin	free-customer-setting.update
admin	missed-call-manager
super-admin	missed-call-manager
support	missed-call-manager
super-admin	next-card-rules.*
next-card-rules.*	next-card-rules.create
next-card-rules.*	next-card-rules.update
next-card-rules.*	next-card-rules.update-common
super-admin	rbac.*
rbac.*	rbac.list-assignment
rbac.*	rbac.list-items
rbac.*	rbac.list-parent-child
super-admin	settings.bonus
admin	settings.free-customer
super-admin	settings.free-customer
super-admin	settings.next-card
stat.for-admin	stat.bonus.affected-payments
stat.for-super-admin	stat.bonus.affected-payments
stat.for-super-admin	stat.bonus.affected-payments-all
stat.for-seller	stat.bonus.affected-payments-my
stat.for-super-admin	stat.bonus.affected-payments.filterByCardStatus
stat.for-super-admin	stat.bonus.affected-payments.filterByCountry
stat.for-super-admin	stat.customer.source
stat.for-super-admin	stat.customer.verification
admin	stat.for-admin
stat.for-super-admin	stat.for-admin
super-admin	stat.for-admin
seller	stat.for-seller
super-admin	stat.for-super-admin
task-manager	stat.for-task-manager
stat.for-admin	stat.operations
stat.for-super-admin	stat.payment-verification.actions-daily
stat.for-super-admin	stat.payment-verification.actions-list
stat.for-super-admin	stat.payment-verification.by-admin
stat.for-super-admin	stat.payment-verification.by-day
stat.for-super-admin	stat.payment.card-payments
stat.for-admin	stat.payment.first-deposit
stat.for-admin	stat.seller.actions-all
stat.for-super-admin	stat.seller.actions-all.filterByCountry
stat.for-admin	stat.seller.actions-daily
stat.for-super-admin	stat.seller.actions-daily.filterByCountry
stat.for-seller	stat.seller.actions-personal
stat.for-admin	stat.seller.actions-total
stat.for-super-admin	stat.seller.actions-total.filterByCountry
stat.for-super-admin	stat.verification.actions-daily
stat.for-task-manager	stat.verification.actions-daily-personal
stat.for-super-admin	stat.verification.actions-list
stat.for-super-admin	stat.verification.by-admin
stat.for-task-manager	stat.verification.by-admin-personal
stat.for-super-admin	stat.verification.by-day
stat.for-super-admin	stat.withdraw.processing
super-admin	system.app-log
system.for-super-admin	system.auth-log
super-admin	system.events-log
super-admin	system.for-super-admin
system.for-super-admin	system.log-transit-manager
admin	system.log-transit-manager
admin	task.all
super-admin	task.all
seller	task.common
support	task.common
task-manager	task.common
task.all	task.common
admin	task.updateAnyTask
super-admin	task.updateAnyTask
task.all	task.updateAnyTask
seller	tools.for-seller
support	tools.for-support
tools.for-seller	tools.line-chat
tools.for-support	tools.line-chat
admin	updateCard
updateOwnCard	updateCard
seller	updateOwnCard
seller	updateOwnTask
task.common	updateOwnTask
admin	updateSupport
seller	updateTask
updateOwnTask	updateTask
user.for-super-admin	user.assignRole
user.for-super-admin	user.assignRole.Admin
user.for-super-admin	user.assignRole.SuperAdmin
user.for-admin	user.changeColor
user.for-super-admin	user.changeCountry
admin	user.for-admin
user.for-super-admin	user.for-admin
super-admin	user.for-super-admin
user.for-admin	user.list
user.for-super-admin	user.save
user.for-super-admin	user.save-new
user.for-super-admin	user.update
user.for-super-admin	user.update-asterisk-id
user.for-super-admin	user.update-currencies
user.for-super-admin	user.update-login
user.for-super-admin	user.update-status
user.for-admin	user.view
admin	customer.call-history
super-admin	customer.call-history
\.


--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.auth_rule (name, data, created_at, updated_at) FROM stdin;
isCardAuthor	O:28:"app\\rbacRules\\CardAuthorRule":3:{s:4:"name";s:12:"isCardAuthor";s:9:"createdAt";i:1480939035;s:9:"updatedAt";i:1480939035;}	1480939035	1480939035
isTaskAuthor	O:28:"app\\rbacRules\\TaskAuthorRule":3:{s:4:"name";s:12:"isTaskAuthor";s:9:"createdAt";i:1479211455;s:9:"updatedAt";i:1479211455;}	1479211455	1479211455
\.



--
-- Data for Name: tasks_groups; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tasks_groups (id, name) FROM stdin;
1	deposit
2	withdrawal
3	verification
4	blocked
5	account
6	application
7	trade
8	log in
9	other
10	money_move
11	payment_missing
\.


COPY public.supports (id, asterisk_id, country_id, admin_id, telegram_chat_id, login, pass, salt, color, status, rules, ip, fio, autocall, last_login, reg_date, access_token, locale, currencies) FROM stdin;
1	117	1	\N	375410764	admin	6eeafaef013319822a1f30407a5353f778b59790	12345	#E6EBE0	work	admin			0	2017-08-30 17:47:12	2016-06-27 16:52:10+03	a74edckF3oNqRHWYRK9wV1SstcDa-maR	\N	["THB"]
2	\N	1	\N	\N	admin_another	6eeafaef013319822a1f30407a5353f778b59790	12345	\N	work	user			0	2016-07-08 18:59:53	2016-07-08 14:59:36+03	suztviXAWaEEIELdWzQsfcRSS_xCBGtB	\N	\N
3	105	1	\N	402840486	seller	6eeafaef013319822a1f30407a5353f778b59790	12345	#4aad7f	work	user			0	2017-08-30 16:34:20	2016-08-04 15:43:50+03	b9_xjM5xOHBjKnh1McRxymwMcfX5oe62	\N	["THB"]
4	108	2	\N	\N	seller_another	6eeafaef013319822a1f30407a5353f778b59790	12345	#fd6596	work	user			0	2017-08-30 16:36:59	2016-08-04 15:43:50+03	eX9LfmAUsxWio_KROA2RVCWMeOSN44cD	\N	["HKD"]
5	110	1	\N	390995686	super-admin	6eeafaef013319822a1f30407a5353f778b59790	12345	#e0c45d	work	user			0	2017-08-30 19:43:37	2016-08-04 15:44:23+03	VaK8q-AR0ZwUN5L9tiJ4R4VihCieRTIL	\N	["THB","VND","HKD","USD","PHP"]
6	107	1	\N	\N	super-admin_another	6eeafaef013319822a1f30407a5353f778b59790	12345	#60bdff	work	user			0	2017-09-05 16:06:54	2016-08-04 15:44:23+03	8b1Xo1sYdTMvJFdDp8g4Da8r4wC1sKx6	\N	["THB"]
7	112	1	\N	\N	support	6eeafaef013319822a1f30407a5353f778b59790	12345	#faa	work	user			0	2016-09-22 21:24:41	2016-08-04 15:44:23+03	s0rHliGyFJF0G2T6KQ6kRwtGBhDnXVoV	\N	["THB"]
8	106	1	\N	400556604	support_another	6eeafaef013319822a1f30407a5353f778b59790	12345	#ccc	work	user	\N		0	2017-08-30 16:38:03	2016-10-03 13:02:02+03	tyCWhZ2-3Q-pJMTbQ8YZM1bH_1cMBTnV	\N	["THB"]
9	109	1	23	422383787	task-manager	6eeafaef013319822a1f30407a5353f778b59790	12345	#aaa	work	user	\N		0	2017-09-05 17:55:57	2016-10-03 13:03:17+03	03lX1vrz0nFCUxPB0zWApQm15m7vUk2A	\N	["THB","VND","HKD","USD","PHP"]
10	111	1	2	406546939	task-manager_another	6eeafaef013319822a1f30407a5353f778b59790	12345	#faa	work	user	\N		0	2017-09-05 12:53:01	2016-10-03 13:17:12+03	O72lpMrf_21LiyDNngmyQ2iSoEuAxIyI	\N	["THB"]
113	123	1	\N	\N	Bird	6eeafaef013319822a1f30407a5353f778b59790	12345	#faa	fired	user	\N		0	2017-09-05 12:53:01	2016-10-03 13:17:12+03	O72lpMrf_21LiyDNngmyQ2iSoEuAxIyI	\N	["THB"]
114	\N	1	\N	\N	robocall		12345	#faa	work	user	\N		0	2017-09-05 12:53:01	2016-10-03 13:17:12+03	O72lpMrf_21LiyDNngmyQ2iSoEuAxIyI	\N	["THB"]
\.

INSERT INTO public.cards (id, created, updated, affected_date, user_id, customer_id, status, recall_date, decline_reason_id, opt_name, opt_phone)
VALUES
  (296968, '2017-09-05 17:58:24', '2017-09-05 13:58:24', null, 8, 314017, 1, null,null, null, 45454545),
  (296969, '2017-09-05 17:58:24', '2017-09-05 13:58:24', null, 3, 333460, 1, null,null, null, 45454545)
;


INSERT INTO public.cards_changes (id, created, card_id, user_id, type, status)
VALUES
  (675978	,'2017-09-05 16:03:05',	296968,	6	,1,	3),
  (675981	,'2017-09-05 16:03:47',	296968,	6	,3,	null),
  (676309	,'2017-09-05 16:52:00',	296968,	5	,3,	null),
  (676310	,'2017-09-05 16:52:04',	296968,	5	,1,	4)
;


COPY public.countries (id, created, name, code, currency, timezone) FROM stdin;
1	2017-11-07 16:20:11	Russia	rus	RUB	Europe/Moscow
2	2017-11-27 16:20:11	finland	fin	EUR	Europe/Helsinki
3	2017-11-28 14:30:11	Thailand	THA	THB	Asia/Bangkok
\.
--
-- Name: affected_customers_payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.affected_customers_payments_id_seq', 14054, true);


--
-- Name: app_events_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_events_log_id_seq', 1, true);


--
-- Name: app_events_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_events_types_id_seq', 10, true);


--
-- Name: app_locales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_locales_id_seq', 1, true);


--
-- Name: app_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_settings_id_seq', 1, true);


--
-- Name: asterisk_selected_customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.asterisk_selected_customers_id_seq', 10, true);


--
-- Name: asterisk_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.asterisk_settings_id_seq', 1, true);


--
-- Name: auth_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.auth_log_id_seq', 1, true);







--
-- Name: card_customers_payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.card_customers_payments_id_seq', 1, true);


--
-- Name: cards_changes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cards_changes_id_seq', 676664, true);


--
-- Name: cards_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cards_comments_id_seq', 368199, true);


--
-- Name: cards_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cards_id_seq', 296974, true);


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.countries_id_seq', 3, true);


--
-- Name: customer_balance_eq_deposit_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.customer_balance_eq_deposit_comments_id_seq', 10, true);


--
-- Name: customer_basis_rewards_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.customer_basis_rewards_id_seq', 221998, true);






--
-- Name: customers_balance_eq_deposit_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.customers_balance_eq_deposit_id_seq', 20, true);


--
-- Name: decline_reason_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.decline_reason_id_seq', 11, true);


--
-- Name: free_customers_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.free_customers_settings_id_seq', 2, true);



--
-- Name: log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.log_id_seq', 1, true);


--
-- Name: log_transits_manager_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.log_transits_manager_id_seq', 1, true);


--
-- Name: next_card_common_rules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.next_card_common_rules_id_seq', 1, true);


--
-- Name: next_card_common_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.next_card_common_settings_id_seq', 7, true);


--
-- Name: next_card_picked_customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.next_card_picked_customers_id_seq', 10, true);


--
-- Name: next_card_user_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.next_card_user_settings_id_seq', 9, true);




--
-- Name: supports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.supports_id_seq', 114, true);


--
-- Name: tasks_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tasks_comments_id_seq', 8, true);


--
-- Name: tasks_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tasks_files_id_seq', 9, true);


--
-- Name: tasks_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tasks_groups_id_seq', 11, true);


--
-- Name: tasks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tasks_id_seq', 11, true);


--
-- Name: verification_stat_by_day_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.verification_stat_by_day_id_seq', 1, true);


ALTER TABLE ONLY public.affected_customers
    ADD CONSTRAINT idx_25632_primary PRIMARY KEY (id);

--
-- Name: affected_customers_payments idx_25633_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affected_customers_payments
    ADD CONSTRAINT idx_25633_primary PRIMARY KEY (id);


--
-- Name: app_events_log idx_25640_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_log
    ADD CONSTRAINT idx_25640_primary PRIMARY KEY (id);


--
-- Name: app_events_types idx_25651_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_types
    ADD CONSTRAINT idx_25651_primary PRIMARY KEY (id);


--
-- Name: app_locales idx_25657_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_locales
    ADD CONSTRAINT idx_25657_primary PRIMARY KEY (id);


--
-- Name: app_settings idx_25663_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_settings
    ADD CONSTRAINT idx_25663_primary PRIMARY KEY (id);


--
-- Name: asterisk_selected_customers idx_25673_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asterisk_selected_customers
    ADD CONSTRAINT idx_25673_primary PRIMARY KEY (id);


--
-- Name: asterisk_settings idx_25681_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asterisk_settings
    ADD CONSTRAINT idx_25681_primary PRIMARY KEY (id);


--
-- Name: auth_assignment idx_25696_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT idx_25696_primary PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item idx_25699_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT idx_25699_primary PRIMARY KEY (name);


--
-- Name: auth_item_child idx_25705_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT idx_25705_primary PRIMARY KEY (parent, child);


--
-- Name: auth_log idx_25710_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_log
    ADD CONSTRAINT idx_25710_primary PRIMARY KEY (id);


--
-- Name: auth_rule idx_25718_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_rule
    ADD CONSTRAINT idx_25718_primary PRIMARY KEY (name);

--
-- Name: bonuses idx_25732_primary; Type: CONSTRAINT; Schema: public; Owner: -
--






--
-- Name: cards idx_25747_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards
    ADD CONSTRAINT idx_25747_primary PRIMARY KEY (id);


--
-- Name: cards_changes idx_25761_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_changes
    ADD CONSTRAINT idx_25761_primary PRIMARY KEY (id);


--
-- Name: cards_comments idx_25769_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_comments
    ADD CONSTRAINT idx_25769_primary PRIMARY KEY (id);


--
-- Name: card_customers_payments idx_25779_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.card_customers_payments
    ADD CONSTRAINT idx_25779_primary PRIMARY KEY (id);


--
-- Name: countries idx_25787_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT idx_25787_primary PRIMARY KEY (id);


--
-- Name: customers_balance_eq_deposit idx_25794_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers_balance_eq_deposit
    ADD CONSTRAINT idx_25794_primary PRIMARY KEY (id);


--
-- Name: customer_balance_eq_deposit_comments idx_25805_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_balance_eq_deposit_comments
    ADD CONSTRAINT idx_25805_primary PRIMARY KEY (id);


--
-- Name: customer_basis_rewards idx_25814_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_basis_rewards
    ADD CONSTRAINT idx_25814_primary PRIMARY KEY (id);






--
-- Name: decline_reason idx_25836_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decline_reason
    ADD CONSTRAINT idx_25836_primary PRIMARY KEY (id);


--
-- Name: free_customers_settings idx_25843_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.free_customers_settings
    ADD CONSTRAINT idx_25843_primary PRIMARY KEY (id);


--
-- Name: log idx_25886_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.log
    ADD CONSTRAINT idx_25886_primary PRIMARY KEY (id);


--
-- Name: log_transits_manager idx_25895_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.log_transits_manager
    ADD CONSTRAINT idx_25895_primary PRIMARY KEY (id);


--
-- Name: migration idx_25900_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT idx_25900_primary PRIMARY KEY (version);


--
-- Name: next_card_common_rules idx_25905_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_common_rules
    ADD CONSTRAINT idx_25905_primary PRIMARY KEY (id);


--
-- Name: next_card_common_settings idx_25914_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_common_settings
    ADD CONSTRAINT idx_25914_primary PRIMARY KEY (id);


--
-- Name: next_card_picked_customers idx_25923_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_picked_customers
    ADD CONSTRAINT idx_25923_primary PRIMARY KEY (id);


--
-- Name: next_card_user_settings idx_25930_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_user_settings
    ADD CONSTRAINT idx_25930_primary PRIMARY KEY (id);


--
-- Name: supports idx_25956_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.supports
    ADD CONSTRAINT idx_25956_primary PRIMARY KEY (id);


--
-- Name: tasks idx_25966_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT idx_25966_primary PRIMARY KEY (id);


--
-- Name: tasks_comments idx_25978_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_comments
    ADD CONSTRAINT idx_25978_primary PRIMARY KEY (id);


--
-- Name: tasks_files idx_25988_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_files
    ADD CONSTRAINT idx_25988_primary PRIMARY KEY (id);


--
-- Name: tasks_groups idx_25998_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_groups
    ADD CONSTRAINT idx_25998_primary PRIMARY KEY (id);


--
-- Name: verification_stat_by_day idx_26007_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_stat_by_day
    ADD CONSTRAINT idx_26007_primary PRIMARY KEY (id);

--
-- Name: idx_25633_unique_payment_id; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25633_unique_payment_id ON public.affected_customers_payments USING btree (payment_id);


CREATE UNIQUE INDEX payments_bonus_unique_payment_id ON public.payments_bonus USING btree (payment_id);

--
-- Name: idx_25640_fk_app_event_log_user_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25640_fk_app_event_log_user_to_user ON public.app_events_log USING btree (user_id);


--
-- Name: idx_25640_fk_app_event_type_to_type; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25640_fk_app_event_type_to_type ON public.app_events_log USING btree (type_id);


--
-- Name: idx_25657_app_local_currency; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25657_app_local_currency ON public.app_locales USING btree (currency);


--
-- Name: idx_25657_app_local_name; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25657_app_local_name ON public.app_locales USING btree (name);


--
-- Name: idx_25673_asterisk_selected_unique_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25673_asterisk_selected_unique_customer ON public.asterisk_selected_customers USING btree (customer_id);


--
-- Name: idx_25699_idx-auth_item-type; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_25699_idx-auth_item-type" ON public.auth_item USING btree (type);


--
-- Name: idx_25699_rule_name; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25699_rule_name ON public.auth_item USING btree (rule_name);


--
-- Name: idx_25705_child; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25705_child ON public.auth_item_child USING btree (child);


--
-- Name: idx_25710_fk_auth_log_user_id_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25710_fk_auth_log_user_id_to_user ON public.auth_log USING btree (user_id);



--
-- Name: idx_25747_fk_card_support_to_support; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25747_fk_card_support_to_support ON public.cards USING btree (user_id);


--
-- Name: idx_25747_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25747_status_index ON public.cards USING btree (status);


--
-- Name: idx_25747_unique_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25747_unique_customer ON public.cards USING btree (customer_id);


--
-- Name: idx_25761_fk_card_change_to_card; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25761_fk_card_change_to_card ON public.cards_changes USING btree (card_id);


--
-- Name: idx_25761_fk_card_change_user_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25761_fk_card_change_user_to_user ON public.cards_changes USING btree (user_id);


--
-- Name: idx_25769_fk_card_comment_card_id_to_card; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25769_fk_card_comment_card_id_to_card ON public.cards_comments USING btree (card_id);


--
-- Name: idx_25769_fk_card_comment_user_id_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25769_fk_card_comment_user_id_to_user ON public.cards_comments USING btree (user_id);


--
-- Name: idx_25794_unique_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25794_unique_customer ON public.customers_balance_eq_deposit USING btree (customer_id);


--
-- Name: idx_25805_customer_id; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25805_customer_id ON public.customer_balance_eq_deposit_comments USING btree (customer_id);


--
-- Name: idx_25805_customer_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25805_customer_index ON public.customer_balance_eq_deposit_comments USING btree (customer_id);


--
-- Name: idx_25814_fk_customer_basis_rewards_to_card_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25814_fk_customer_basis_rewards_to_card_customer ON public.customer_basis_rewards USING btree (customer_id);


--
-- Name: idx_25814_fk_seller_basis_rewards_to_seller; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25814_fk_seller_basis_rewards_to_seller ON public.customer_basis_rewards USING btree (seller_id);


--
-- Name: idx_25814_unique_reward_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25814_unique_reward_customer ON public.customer_basis_rewards USING btree (customer_id);


--
-- Name: idx_25819_customer_basis_rewards_calculate_customer_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25819_customer_basis_rewards_calculate_customer_id ON public.customer_basis_reward_calculate USING btree (customer_id);


--
-- Name: idx_25843_currency; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25843_currency ON public.free_customers_settings USING btree (currency);



--
-- Name: idx_25886_idx_log_category; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25886_idx_log_category ON public.log USING btree (category);


--
-- Name: idx_25886_idx_log_level; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25886_idx_log_level ON public.log USING btree (level);


--
-- Name: idx_25914_currency; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25914_currency ON public.next_card_common_settings USING btree (currency);


--
-- Name: idx_25923_next_card_picked_unique_customer; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25923_next_card_picked_unique_customer ON public.next_card_picked_customers USING btree (customer_id);


--
-- Name: idx_25930_fk_supports_rules_user_id_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25930_fk_supports_rules_user_id_to_user ON public.next_card_user_settings USING btree (user_id);


--
-- Name: idx_25956_log; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25956_log ON public.supports USING btree (login);


--
-- Name: idx_25956_seller_unique_asterisk_id; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25956_seller_unique_asterisk_id ON public.supports USING btree (asterisk_id);


--
-- Name: idx_25956_user_unique_admin_id; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25956_user_unique_admin_id ON public.supports USING btree (admin_id);


--
-- Name: idx_25956_user_unique_asterisk_id_for_country; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25956_user_unique_asterisk_id_for_country ON public.supports USING btree (asterisk_id, country_id);


--
-- Name: idx_25956_user_unique_telegram_chat_id; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_25956_user_unique_telegram_chat_id ON public.supports USING btree (telegram_chat_id);


--
-- Name: idx_25966_fk_task_to_appointed; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25966_fk_task_to_appointed ON public.tasks USING btree (appointed_to_id);


--
-- Name: idx_25966_fk_task_to_creator; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25966_fk_task_to_creator ON public.tasks USING btree (creator_id);


--
-- Name: idx_25966_fk_task_to_task_group; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25966_fk_task_to_task_group ON public.tasks USING btree (group_id);


--
-- Name: idx_25966_task_currency; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25966_task_currency ON public.tasks USING btree (currency);


--
-- Name: idx_25978_fk_comment_task_id_to_task; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25978_fk_comment_task_id_to_task ON public.tasks_comments USING btree (task_id);


--
-- Name: idx_25978_fk_comment_user_id_to_user; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25978_fk_comment_user_id_to_user ON public.tasks_comments USING btree (user_id);


--
-- Name: idx_25988_fk_file_task_id_to_task; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25988_fk_file_task_id_to_task ON public.tasks_files USING btree (task_id);


--
-- Name: idx_26007_verification_stat_by_day_unique_composed_data; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_26007_verification_stat_by_day_unique_composed_data ON public.verification_stat_by_day USING btree (day, mode, admin_id);


--
-- Name: affected_customers_payments on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.affected_customers_payments FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_affected_customers_payments();


CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.payments_bonus FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_payments_bonus();


--
-- Name: app_events_log on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.app_events_log FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_app_events_log();


--
-- Name: asterisk_selected_customers on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.asterisk_selected_customers FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_asterisk_selected_customers();




--
-- Name: cards on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.cards FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_cards();


--
-- Name: card_customers_payments on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.card_customers_payments FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_card_customers_payments();


--
-- Name: customer_basis_rewards on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.customer_basis_rewards FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_customer_basis_rewards();



--
-- Name: log_transits_manager on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.log_transits_manager FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_log_transits_manager();


--
-- Name: tasks on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.tasks FOR EACH ROW EXECUTE PROCEDURE public.on_update_current_timestamp_tasks();


--
-- Name: auth_assignment auth_assignment_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_ibfk_1 FOREIGN KEY (item_name) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_ibfk_1 FOREIGN KEY (parent) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_ibfk_2 FOREIGN KEY (child) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item auth_item_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT auth_item_ibfk_1 FOREIGN KEY (rule_name) REFERENCES public.auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: app_events_log fk_app_event_log_user_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_log
    ADD CONSTRAINT fk_app_event_log_user_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: app_events_log fk_app_event_type_to_type; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_events_log
    ADD CONSTRAINT fk_app_event_type_to_type FOREIGN KEY (type_id) REFERENCES public.app_events_types(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_log fk_auth_log_user_id_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_log
    ADD CONSTRAINT fk_auth_log_user_id_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;




--
-- Name: cards_changes fk_card_change_to_card; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_changes
    ADD CONSTRAINT fk_card_change_to_card FOREIGN KEY (card_id) REFERENCES public.cards(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cards_changes fk_card_change_user_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_changes
    ADD CONSTRAINT fk_card_change_user_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cards_comments fk_card_comment_card_id_to_card; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_comments
    ADD CONSTRAINT fk_card_comment_card_id_to_card FOREIGN KEY (card_id) REFERENCES public.cards(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cards_comments fk_card_comment_user_id_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards_comments
    ADD CONSTRAINT fk_card_comment_user_id_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cards fk_card_support_to_support; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cards
    ADD CONSTRAINT fk_card_support_to_support FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks_comments fk_comment_task_id_to_task; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_comments
    ADD CONSTRAINT fk_comment_task_id_to_task FOREIGN KEY (task_id) REFERENCES public.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks_comments fk_comment_user_id_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_comments
    ADD CONSTRAINT fk_comment_user_id_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: customer_basis_rewards fk_customer_basis_rewards_to_card_customer; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_basis_rewards
    ADD CONSTRAINT fk_customer_basis_rewards_to_card_customer FOREIGN KEY (customer_id) REFERENCES public.cards(customer_id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: tasks_files fk_file_task_id_to_task; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_files
    ADD CONSTRAINT fk_file_task_id_to_task FOREIGN KEY (task_id) REFERENCES public.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: customer_basis_rewards fk_seller_basis_rewards_to_seller; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_basis_rewards
    ADD CONSTRAINT fk_seller_basis_rewards_to_seller FOREIGN KEY (seller_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: next_card_user_settings fk_supports_rules_user_id_to_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.next_card_user_settings
    ADD CONSTRAINT fk_supports_rules_user_id_to_user FOREIGN KEY (user_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks fk_task_to_appointed; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT fk_task_to_appointed FOREIGN KEY (appointed_to_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks fk_task_to_creator; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT fk_task_to_creator FOREIGN KEY (creator_id) REFERENCES public.supports(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks fk_task_to_task_group; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT fk_task_to_task_group FOREIGN KEY (group_id) REFERENCES public.tasks_groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

