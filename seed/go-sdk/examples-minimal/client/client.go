// This file was auto-generated by Fern from our API Definition.

package client

import (
	core "github.com/examples-minimal/fern/core"
	internal "github.com/examples-minimal/fern/internal"
	option "github.com/examples-minimal/fern/option"
	service "github.com/examples-minimal/fern/service"
	http "net/http"
)

type Client struct {
	baseURL string
	caller  *internal.Caller
	header  http.Header

	Service *service.Client
}

func NewClient(opts ...option.RequestOption) *Client {
	options := core.NewRequestOptions(opts...)
	return &Client{
		baseURL: options.BaseURL,
		caller: internal.NewCaller(
			&internal.CallerParams{
				Client:      options.HTTPClient,
				MaxAttempts: options.MaxAttempts,
			},
		),
		header:  options.ToHeader(),
		Service: service.NewClient(opts...),
	}
}
